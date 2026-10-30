<?php

namespace Spdotdev\SplotnikovDev\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * One valid submission of the contact form, stored before any mail is tried so a message is never lost when mail
 * is not available. The IP address is never stored, only a keyed hash of it.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $about
 * @property string $message
 * @property string $language
 * @property string|null $page
 * @property string|null $ip_hash
 * @property string $mail_status
 * @property Carbon|null $mailed_at
 * @property bool $is_test
 * @property Carbon|null $created_at
 */
class ContactLead extends Model
{
    protected $table = 'splotnikov_contact_leads';

    protected $guarded = [];

    protected $casts = ['is_test' => 'boolean', 'mailed_at' => 'datetime'];
}
