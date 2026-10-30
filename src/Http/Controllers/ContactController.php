<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spdotdev\SplotnikovDev\Mail\ContactMessage;
use Spdotdev\SplotnikovDev\Models\ContactLead;

/**
 * The contact form: validated, rate limited, bot-checked (Cloudflare Turnstile
 * when keys are configured, otherwise a honeypot plus a signed minimum fill
 * time), then mailed to the configured address.
 */
class ContactController
{
    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', $this->noControlChars(false)],
            'email' => ['required', 'email', 'max:160', $this->noControlChars(false)],
            'about' => ['nullable', 'string', 'max:120', $this->noControlChars(false)],
            'message' => ['required', 'string', 'min:10', 'max:4000', $this->noControlChars(true)],
            'website' => ['nullable', 'max:0'], // honeypot: humans never see it
            't' => ['required', 'string'],
            'cf-turnstile-response' => ['nullable', 'string'],
        ], ['website.max' => 'Something went wrong, please email me directly.']);

        $to = (string) config('splotnikov-dev.contact_to', 'stplotnikov@gmail.com');

        if (! $this->humanEnough($request, $data)) {
            return back()->withInput()->withErrors(['message' => 'The bot check failed. Please try again or email '.$to.' directly.']);
        }

        // Store first. A valid message is never lost because mail is down; success is shown only when it is stored.
        $path = (string) parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH);
        try {
            $lead = ContactLead::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'about' => $data['about'] ?? null,
                'message' => $data['message'],
                'language' => $path === '/nl' || str_starts_with($path, '/nl/') ? 'nl' : 'en',
                'page' => $path !== '' ? mb_substr($path, 0, 200) : null,
                'ip_hash' => substr(hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')), 0, 32),
                'is_test' => str_starts_with(strtolower($data['name']), '[test]'),
            ]);
        } catch (\Throwable $e) {
            Log::error('splotnikov.dev contact form: the message could not be stored', self::safeContext($e));

            return back()->withInput()->withErrors(['message' => 'Your message could not be saved right now, and I do not want you to think it arrived. Please email '.$to.' directly or book a call.']);
        }

        $mailer = null;
        if ((string) config('splotnikov-dev.mail_dsn', '') !== '') {
            config(['mail.mailers.contact_form' => ['transport' => 'smtp', 'url' => (string) config('splotnikov-dev.mail_dsn')]]);
            $mailer = 'contact_form';
        }

        if ($mailer === null && config('splotnikov-dev.require_real_mailer', true) && in_array((string) config('mail.default'), ['log', 'array'], true)) {
            Log::warning('splotnikov.dev contact form: no mail transport (set SPLOTNIKOV_CONTACT_MAIL_DSN and SPLOTNIKOV_CONTACT_MAIL_FROM); lead #'.$lead->id.' is stored, no mail was sent. List: php artisan splotnikov:contact-leads --unmailed');
            $lead->update(['mail_status' => 'not_configured']);

            return back()->with('contact_sent', 'stored');
        }

        try {
            Mail::mailer($mailer)->to($to)
                ->send(new ContactMessage($data['name'], $data['email'], (string) ($data['about'] ?? ''), $data['message'], (string) $lead->ip_hash));
            $lead->update(['mail_status' => 'sent', 'mailed_at' => now()]);
        } catch (\Throwable $e) {
            Log::error('splotnikov.dev contact form could not send mail; lead #'.$lead->id.' is stored', self::safeContext($e));
            $lead->update(['mail_status' => 'failed']);

            return back()->with('contact_sent', 'stored');
        }

        return back()->with('contact_sent', 'mailed');
    }

    /**
     * Rejects control characters (and invalid UTF-8) so a submission cannot carry terminal escape sequences into the
     * admin's console. The message may keep newline and tab.
     */
    private function noControlChars(bool $multiline): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) use ($multiline): void {
            $v = (string) $value;
            $pattern = $multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x{80}-\x{9F}]/u' : '/[\x00-\x1F\x7F\x{80}-\x{9F}]/u';
            if (! mb_check_encoding($v, 'UTF-8') || preg_match($pattern, $v) === 1) {
                $fail('The :attribute contains characters that are not allowed.');
            }
        };
    }

    /**
     * What may go into a log line about a failure: the exception class and, for a database error, the SQLSTATE.
     * Never the message (it can hold the SQL and the bound visitor values) and never the bindings.
     *
     * @return array<string, string>
     */
    private static function safeContext(\Throwable $e): array
    {
        $ctx = ['exception' => get_class($e)];
        if ($e instanceof QueryException) {
            $ctx['sqlstate'] = (string) ($e->errorInfo[0] ?? $e->getCode());
        }

        return $ctx;
    }

    /**
     * A signed timestamp the form carries; the form must be at least 3 seconds old.
     */
    public static function token(): string
    {
        $t = (string) time();

        return $t.'.'.hash_hmac('sha256', $t, (string) config('app.key'));
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function humanEnough(Request $request, array $data): bool
    {
        [$t, $sig] = array_pad(explode('.', (string) $data['t'], 2), 2, '');
        if (! hash_equals(hash_hmac('sha256', $t, (string) config('app.key')), $sig)) {
            return false;
        }
        if (time() - (int) $t < 3 || time() - (int) $t > 6 * 3600) {
            return false;
        }

        $secret = (string) config('splotnikov-dev.turnstile_secret');
        if ($secret === '') {
            return true;
        }
        $token = (string) ($data['cf-turnstile-response'] ?? '');
        if ($token === '') {
            return false;
        }
        try {
            $r = Http::asForm()->timeout(8)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret, 'response' => $token, 'remoteip' => $request->ip(),
            ]);

            return (bool) $r->json('success', false);
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification failed', self::safeContext($e));

            return false;
        }
    }
}
