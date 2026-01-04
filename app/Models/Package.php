<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $table = 'packages';
    protected $primaryKey = 'package_id';
    protected $fillable = [
        'package_name',
        'tier',
        'package_icon',
        'package_price',
        'stripe_id',
    ];

    public $hidden = ['created_at', 'updated_at'];
}
