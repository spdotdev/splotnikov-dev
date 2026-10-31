<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
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
        $nl = self::isDutch($request);
        $validator = Validator::make($request->all(), $this->rules($nl), self::messages($nl));
        if ($validator->fails()) {
            // The page carries both languages (the EN/NL switch flips them), so list the errors in both.
            $en = Validator::make($request->all(), $this->rules(false), self::messages(false))->errors()->all();
            $du = Validator::make($request->all(), $this->rules(true), self::messages(true))->errors()->all();

            return back()->withInput()->withErrors($validator)
                ->with('contact_errors_en', $en)->with('contact_errors_nl', $du);
        }
        $data = $validator->validated();

        $to = (string) config('splotnikov-dev.contact_to', 'stplotnikov@gmail.com');

        if (! $this->humanEnough($request, $data)) {
            return $this->failure($nl,
                'The bot check failed. Please try again or email '.$to.' directly.',
                'De botcontrole is mislukt. Probeer het opnieuw of mail direct naar '.$to.'.');
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

            return $this->failure($nl,
                'Your message could not be saved right now, and I do not want you to think it arrived. Please email '.$to.' directly or book a call.',
                'Je bericht kon nu niet worden bewaard, en ik wil niet dat je denkt dat het is aangekomen. Mail direct naar '.$to.' of plan een gesprek.');
        }

        $mailer = null;
        if ((string) config('splotnikov-dev.mail_dsn', '') !== '') {
            config(['mail.mailers.contact_form' => ['transport' => 'smtp', 'url' => (string) config('splotnikov-dev.mail_dsn')]]);
            $mailer = 'contact_form';
        }

        if ($mailer === null && config('splotnikov-dev.require_real_mailer', true) && in_array((string) config('mail.default'), ['log', 'array'], true)) {
            Log::critical('splotnikov.dev contact form: no mail transport (set SPLOTNIKOV_CONTACT_MAIL_DSN and SPLOTNIKOV_CONTACT_MAIL_FROM); lead #'.$lead->id.' is stored, no mail was sent. List: php artisan splotnikov:contact-leads --unmailed');
            $lead->update(['mail_status' => 'not_configured']);

            return back()->with('contact_sent', 'stored');
        }

        try {
            Mail::mailer($mailer)->to($to)
                ->send(new ContactMessage($data['name'], $data['email'], (string) ($data['about'] ?? ''), $data['message'], (string) $lead->ip_hash));
            $lead->update(['mail_status' => 'sent', 'mailed_at' => now()]);
        } catch (\Throwable $e) {
            Log::critical('splotnikov.dev contact form could not send mail; lead #'.$lead->id.' is stored', self::safeContext($e));
            $lead->update(['mail_status' => 'failed']);

            return back()->with('contact_sent', 'stored');
        }

        return back()->with('contact_sent', 'mailed');
    }

    /**
     * The validation rules; the text of the hand-written rule depends on the language.
     *
     * @return array<string, array<int, mixed>>
     */
    private function rules(bool $nl): array
    {
        return [
            'name' => ['required', 'string', 'max:120', $this->noControlChars(false, $nl)],
            'email' => ['required', 'email', 'max:160', $this->noControlChars(false, $nl)],
            'about' => ['nullable', 'string', 'max:120', $this->noControlChars(false, $nl)],
            'message' => ['required', 'string', 'min:10', 'max:4000', $this->noControlChars(true, $nl)],
            'website' => ['nullable', 'max:0'], // honeypot: humans never see it
            't' => ['required', 'string'],
            'cf-turnstile-response' => ['nullable', 'string'],
        ];
    }

    /** A failure the visitor must see in both languages at once. */
    private function failure(bool $nl, string $en, string $du): RedirectResponse
    {
        return back()->withInput()->withErrors(['message' => $nl ? $du : $en])
            ->with('contact_errors_en', [$en])->with('contact_errors_nl', [$du]);
    }

    /** True when the form was sent from a Dutch page (/nl/...): the error messages are then Dutch too. */
    private static function isDutch(Request $request): bool
    {
        $path = (string) parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH);

        return $path === '/nl' || str_starts_with($path, '/nl/');
    }

    /**
     * The visible validation messages, written for the form (the host app has no translation files for them).
     *
     * @return array<string, string>
     */
    private static function messages(bool $nl): array
    {
        if ($nl) {
            return [
                'name.required' => 'Vul je naam in.',
                'name.max' => 'Je naam mag hoogstens 120 tekens zijn.',
                'email.required' => 'Vul je e-mailadres in, dan kan ik antwoorden.',
                'email.email' => 'Dit e-mailadres lijkt niet te kloppen. Controleer het even.',
                'email.max' => 'Je e-mailadres mag hoogstens 160 tekens zijn.',
                'about.max' => 'Het onderwerp is te lang.',
                'message.required' => 'Schrijf een bericht, dan weet ik waarmee ik kan helpen.',
                'message.min' => 'Je bericht is te kort. Schrijf minstens 10 tekens.',
                'message.max' => 'Je bericht mag hoogstens 4000 tekens zijn.',
                't.required' => 'Er ging iets mis. Laad de pagina opnieuw en probeer het nog een keer.',
                'website.max' => 'Er ging iets mis. Mail me direct.',
            ];
        }

        return [
            'name.required' => 'Please enter your name.',
            'name.max' => 'Your name can be 120 characters at most.',
            'email.required' => 'Please enter your email address so I can reply.',
            'email.email' => 'That email address does not look right. Please check it.',
            'email.max' => 'Your email address can be 160 characters at most.',
            'about.max' => 'The topic is too long.',
            'message.required' => 'Please write a message so I know what to help with.',
            'message.min' => 'Your message is too short. Please write at least 10 characters.',
            'message.max' => 'Your message can be 4000 characters at most.',
            't.required' => 'Something went wrong. Please reload the page and try again.',
            'website.max' => 'Something went wrong, please email me directly.',
        ];
    }

    /**
     * Rejects control characters (and invalid UTF-8) so a submission cannot carry terminal escape sequences into the
     * admin's console. The message may keep newline and tab.
     */
    private function noControlChars(bool $multiline, bool $nl = false): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail) use ($multiline, $nl): void {
            $v = (string) $value;
            $pattern = $multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x{80}-\x{9F}]/u' : '/[\x00-\x1F\x7F\x{80}-\x{9F}]/u';
            if (! mb_check_encoding($v, 'UTF-8') || preg_match($pattern, $v) === 1) {
                $fail($nl ? 'Dit veld bevat tekens die niet zijn toegestaan.' : 'The :attribute contains characters that are not allowed.');
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
