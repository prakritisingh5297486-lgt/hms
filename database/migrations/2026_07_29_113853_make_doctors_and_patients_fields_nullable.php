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
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('department')->nullable()->change();
            $table->string('license_id')->nullable()->change();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->string('age')->nullable()->change();
            $table->string('gender')->nullable()->change();
            $table->string('blood_group')->nullable()->change();
            $table->string('disease')->nullable()->change();
            $table->string('number')->nullable()->change();
            $table->string('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('department')->nullable(false)->change();
            $table->string('license_id')->nullable(false)->change();
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->string('age')->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
            $table->string('blood_group')->nullable(false)->change();
            $table->string('disease')->nullable(false)->change();
            $table->string('number')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
        });
    }
};
