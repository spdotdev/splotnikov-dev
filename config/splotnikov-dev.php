<?php

return [
    // Host that this site answers on. Override per-environment with
    // SPLOTNIKOV_DOMAIN (e.g. set to your local host when verifying).
    'domain' => env('SPLOTNIKOV_DOMAIN', 'splotnikov.dev'),

    // The public booking page behind "Book a call" on the contact page.
    'booking_url' => env('SPLOTNIKOV_BOOKING_URL', 'https://plan.scuttle.dev/book/stas'),

    // Where the contact form delivers.
    'contact_to' => env('SPLOTNIKOV_CONTACT_TO', 'stplotnikov@gmail.com'),

    // Cloudflare Turnstile (the bot check on the contact form). Without keys the
    // form falls back to a honeypot plus a signed minimum fill time.
    'turnstile_site_key' => env('SPLOTNIKOV_TURNSTILE_SITE_KEY', ''),
    'turnstile_secret' => env('SPLOTNIKOV_TURNSTILE_SECRET', ''),
];
