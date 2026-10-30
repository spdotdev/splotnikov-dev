<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('splotnikov_contact_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 160);
            $table->string('about', 120)->nullable();
            $table->text('message');
            $table->string('language', 2)->default('en');
            $table->string('page', 200)->nullable();
            $table->string('ip_hash', 32)->nullable();
            // pending: stored, no mail tried yet; sent: mail accepted by the transport; failed: the transport threw;
            // not_configured: the host has no real mail transport, so none was tried.
            $table->string('mail_status', 20)->default('pending');
            $table->timestamp('mailed_at')->nullable();
            $table->boolean('is_test')->default(false);
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('splotnikov_contact_leads');
    }
};
