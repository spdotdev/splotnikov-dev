<?php

namespace Spdotdev\SplotnikovDev\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spdotdev\SplotnikovDev\Mail\ContactMessage;

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
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'about' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
            'website' => ['nullable', 'max:0'], // honeypot: humans never see it
            't' => ['required', 'string'],
            'cf-turnstile-response' => ['nullable', 'string'],
        ], ['website.max' => 'Something went wrong, please email me directly.']);

        $to = (string) config('splotnikov-dev.contact_to', 'stplotnikov@gmail.com');

        if (! $this->humanEnough($request, $data)) {
            return back()->withInput()->withErrors(['message' => 'The bot check failed. Please try again or email '.$to.' directly.']);
        }

        $mailer = null;
        if ((string) config('splotnikov-dev.mail_dsn', '') !== '') {
            config(['mail.mailers.contact_form' => ['transport' => 'smtp', 'url' => (string) config('splotnikov-dev.mail_dsn')]]);
            $mailer = 'contact_form';
        }

        if ($mailer === null && config('splotnikov-dev.require_real_mailer', true) && in_array((string) config('mail.default'), ['log', 'array'], true)) {
            Log::error('splotnikov.dev contact form: the default mailer is '.config('mail.default').', so the message is not delivered', ['from' => $data['email']]);

            return back()->withInput()->withErrors(['message' => 'Mail from this form is not working right now, and I do not want you to think it arrived. Please email '.$to.' directly or book a call.']);
        }

        try {
            Mail::mailer($mailer)->to($to)
                ->send(new ContactMessage($data['name'], $data['email'], (string) ($data['about'] ?? ''), $data['message'], (string) $request->ip()));
        } catch (\Throwable $e) {
            Log::error('splotnikov.dev contact form could not send mail', ['error' => $e->getMessage()]);

            return back()->withInput()->withErrors(['message' => 'The message could not be sent right now. Please email '.$to.' directly.']);
        }

        return back()->with('contact_sent', true);
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
            Log::warning('Turnstile verification failed', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
