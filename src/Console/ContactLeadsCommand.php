<?php

namespace Spdotdev\SplotnikovDev\Console;

use Illuminate\Console\Command;
use Spdotdev\SplotnikovDev\Models\ContactLead;
use Symfony\Component\Console\Formatter\OutputFormatter;

class ContactLeadsCommand extends Command
{
    protected $signature = 'splotnikov:contact-leads {--limit=20 : How many of the newest to list} {--id= : Show one lead in full} {--unmailed : Only leads whose mail was not sent} {--purge-test : Delete the rows marked as test (name starts with [TEST])}';

    protected $description = 'List the contact form submissions stored by splotnikov.dev (read-only except --purge-test)';

    public function handle(): int
    {
        if ($this->option('purge-test')) {
            $n = ContactLead::query()->where('is_test', true)->delete();
            $this->info("Deleted $n test lead(s).");

            return self::SUCCESS;
        }

        if ($this->option('id') !== null) {
            $l = ContactLead::query()->find((int) $this->option('id'));
            if ($l === null) {
                $this->error('No such lead.');

                return self::FAILURE;
            }
            foreach (['id', 'created_at', 'name', 'email', 'about', 'language', 'page', 'mail_status', 'mailed_at', 'is_test'] as $k) {
                $this->line(str_pad($k, 12).' '.self::safe((string) $l->getAttribute($k)));
            }
            $this->line('');
            $this->line(self::safe($l->message));

            return self::SUCCESS;
        }

        $q = ContactLead::query()->latest('id');
        if ($this->option('unmailed')) {
            $q->where('mail_status', '!=', 'sent');
        }
        $rows = $q->limit(max(1, (int) $this->option('limit')))->get();
        $this->table(['id', 'received', 'name', 'email', 'lang', 'about', 'mail', 'test', 'message'], $rows->map(fn (ContactLead $l) => [
            $l->id, (string) $l->created_at, self::safe($l->name), self::safe($l->email), self::safe($l->language), self::safe((string) $l->about), self::safe($l->mail_status), $l->is_test ? 'yes' : '', mb_strimwidth(str_replace("\n", ' ', self::safe($l->message)), 0, 60, '...'),
        ])->all());
        $this->line($rows->count().' shown, '.ContactLead::query()->count().' stored in total, '.ContactLead::query()->where('mail_status', '!=', 'sent')->count().' without a sent mail.');

        return self::SUCCESS;
    }

    /**
     * Stored visitor text is untrusted: strip control characters (a terminal escape sequence could rewrite the
     * admin's screen) and escape console style tags before printing.
     */
    private static function safe(string $v): string
    {
        $clean = preg_replace('/[\x00-\x08\x0B-\x1F\x7F\x{80}-\x{9F}]/u', '', $v);

        return OutputFormatter::escape($clean ?? '');
    }
}
