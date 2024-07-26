<?php

namespace Geoslim\Blinqpay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlinqpayPaymentProcessor extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'transaction_fee',
        'reliability',
        'supported_currencies',
        'status'
    ];

    protected $casts = [
        'supported_currencies' => 'json',
    ];
}
