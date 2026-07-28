<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hospital_settings', function (Blueprint $table) {
            $table->string('mail_mailer')->nullable()->after('favicon');
            $table->string('mail_host')->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_encryption')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('hospital_settings', function (Blueprint $table) {
            $table->dropColumn([
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_encryption',
                'mail_username',
                'mail_password',
            ]);
        });
    }
};
