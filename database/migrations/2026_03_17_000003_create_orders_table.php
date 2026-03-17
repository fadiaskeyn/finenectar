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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone', 30);
            $table->text('customer_address');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price');
            $table->unsignedBigInteger('total_amount');
            $table->string('payment_method', 20);
            $table->string('status', 30)->default('pending');
            $table->string('merchant_ref')->nullable()->index();
            $table->string('tripay_reference')->nullable()->index();
            $table->text('tripay_checkout_url')->nullable();
            $table->text('tripay_qr_url')->nullable();
            $table->json('tripay_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
