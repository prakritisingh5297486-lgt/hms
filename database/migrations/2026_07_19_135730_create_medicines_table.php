<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {

            $table->id();

            $table->string('medicine_name');

            $table->string('medicine_code')->unique();

            $table->string('category');

            $table->string('manufacturer')->nullable();

            $table->decimal('price',10,2);

            $table->integer('stock');

            $table->integer('minimum_stock')->default(10);

            $table->date('expiry_date');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};