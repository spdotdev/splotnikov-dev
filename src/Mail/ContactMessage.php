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
        public string $page = '',
    ) {
        // One line each, whatever the caller passes: nothing from a visitor can add a header line to the mail.
        $line = fn (string $v): string => trim((string) preg_replace('/[\x00-\x1F\x7F]+/', ' ', $v));
        $this->name = $line($this->name);
        $this->email = $line($this->email);
        $this->about = $line($this->about);
        $this->page = $line($this->page);
    }

    public function build(): static
    {
        $body = "Name: {$this->name}\nEmail: {$this->email}\nAbout: ".($this->about !== '' ? $this->about : '-')."\nPage: ".($this->page !== '' ? $this->page : '-')."\n\n{$this->text}\n\n--\nSent from the splotnikov.dev contact form · visitor {$this->ip}";

        $from = (string) config('splotnikov-dev.mail_from', '');
        if ($from !== '') {
            $this->from($from, 'splotnikov.dev');
        }

        return $this
            ->subject('splotnikov.dev contact: '.($this->about !== '' ? $this->about : 'general').' · '.$this->name)
            ->replyTo($this->email, $this->name)
            ->html('<pre style="font:14px/1.5 monospace;white-space:pre-wrap">'.e($body).'</pre>')
            // @phpstan-ignore argument.type (the splotnikov:: namespace is registered at runtime via loadViewsFrom)
            ->text('splotnikov::mail.contact-text');
    }
}
