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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('subtotal_amount')->default(0)->after('unit_price');
            $table->unsignedBigInteger('shipping_amount')->default(0)->after('subtotal_amount');
            $table->string('shipping_courier', 30)->nullable()->after('shipping_amount');
            $table->string('shipping_service', 80)->nullable()->after('shipping_courier');
            $table->string('shipping_etd', 80)->nullable()->after('shipping_service');
            $table->string('shipping_destination_id', 30)->nullable()->after('shipping_etd');
            $table->json('shipping_response')->nullable()->after('shipping_destination_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal_amount',
                'shipping_amount',
                'shipping_courier',
                'shipping_service',
                'shipping_etd',
                'shipping_destination_id',
                'shipping_response',
            ]);
        });
    }
};
