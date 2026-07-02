<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'subtotal_amount',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'status',
        'merchant_ref',
        'tripay_reference',
        'tripay_checkout_url',
        'tripay_qr_url',
        'tripay_response',
    ];

    protected $casts = [
        'tripay_response' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
