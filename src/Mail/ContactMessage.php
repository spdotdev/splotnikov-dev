<?php

namespace Spdotdev\SplotnikovDev\Mail;

use Illuminate\Mail\Mailable;

/**
 * A message from the contact form, delivered as plain text.
 */
class ContactMessage extends Mailable
{
    public function __construct(
        public string $name,
        public string $email,
        public string $about,
        public string $text,
        public string $ip,
    ) {}

    public function build(): static
    {
        $body = "Name: {$this->name}\nEmail: {$this->email}\nAbout: ".($this->about !== '' ? $this->about : '-')."\n\n{$this->text}\n\n--\nSent from the splotnikov.dev contact form · IP {$this->ip}";

        return $this
            ->subject('splotnikov.dev contact: '.($this->about !== '' ? $this->about : 'general').' · '.$this->name)
            ->replyTo($this->email, $this->name)
            ->html('<pre style="font:14px/1.5 monospace;white-space:pre-wrap">'.e($body).'</pre>')
            // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom)
            ->text('splotnikov::mail.contact-text');
    }
}
