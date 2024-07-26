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
        Schema::create('blinqpay_payment_processors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('transaction_fee');
            $table->decimal('reliability');
            $table->json('supported_currencies');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blinqpay_payment_processors');
    }
};
