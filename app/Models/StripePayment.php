<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    use HasFactory;
    protected $table = 'stripe_payments';
    protected $fillable = [
        'name',
        'email',
        'payment_status',
        'client_reference_id',
        'amount_total',
    ];
}
