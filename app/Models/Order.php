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
        'quantity',
        'unit_price',
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
}
