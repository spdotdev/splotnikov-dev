<?php

return [
    // Host that this site answers on. Override per-environment with
    // SPLOTNIKOV_DOMAIN (e.g. set to your local host when verifying).
    'domain' => env('SPLOTNIKOV_DOMAIN', 'splotnikov.dev'),

    // The public booking page behind "Book a call" on the contact page.
    'booking_url' => env('SPLOTNIKOV_BOOKING_URL', 'https://plan.scuttle.dev/book/stas'),

    // Where the contact form delivers.
    'contact_to' => env('SPLOTNIKOV_CONTACT_TO', 'stplotnikov@gmail.com'),

    // The form refuses to say "Sent" when the application's default mailer is `log` or `array`: those mailers write the
    // message to a file and deliver nothing (found 2026-10-30: the form answered Sent and no mail arrived). Set false
    // only in tests.
    'require_real_mailer' => (bool) env('SPLOTNIKOV_REQUIRE_REAL_MAILER', true),

    // The host application has no mail setup of its own (its default mailer is `log`). Give the form its own: a Symfony
    // mailer DSN such as smtp://user:app-password@smtp.gmail.com:587, and the address the message is sent from (it must
    // be one that SMTP account may send as). Both stay in the host's .env, never in the repository.
    'mail_dsn' => env('SPLOTNIKOV_CONTACT_MAIL_DSN', ''),
    'mail_from' => env('SPLOTNIKOV_CONTACT_MAIL_FROM', ''),

    // Cloudflare Turnstile (the bot check on the contact form). Without keys the
    // form falls back to a honeypot plus a signed minimum fill time.
    'turnstile_site_key' => env('SPLOTNIKOV_TURNSTILE_SITE_KEY', ''),
    'turnstile_secret' => env('SPLOTNIKOV_TURNSTILE_SECRET', ''),
];
