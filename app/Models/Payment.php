<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'user_id',
        'package_tier',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_price_id',
    ];
}
