<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcat extends Model
{
    use HasFactory;
    protected $table = 'subcats';
    protected $primaryKey = 'subcat_id';
    protected $fillable = [
        'subcat_name',
        'subcat_slug',
        'subcat_description',
        'subcat_img',
        'subcat_status',
        'cat_id',
    ];

    public $hidden = ['created_at','updated_at'];
}
