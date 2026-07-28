<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_no')->unique();

            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();

            $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();

            $table->decimal('amount',10,2);

            $table->decimal('discount',10,2)->default(0);

            $table->decimal('tax',10,2)->default(0);

            $table->decimal('total_amount',10,2);

            $table->enum('payment_method',[
                'Cash',
                'UPI',
                'Card',
                'Net Banking',
                'Wallet'
            ]);

            $table->enum('payment_status',[
                'Pending',
                'Paid',
                'Failed',
                'Refunded'
            ])->default('Pending');

            $table->string('transaction_id')->nullable();

            $table->date('payment_date');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
