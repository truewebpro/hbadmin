<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;
    protected $table = 'cats';
    protected $primaryKey = 'cat_id';
    protected $fillable = [
        'cat_name',
        'cat_description',
        'cat_image',
        'cat_slug',
        'cat_status'
    ];

    public $hidden = ['created_at','updated_at'];
    public function subcats()
    {
        return $this->hasMany(Subcat::class, 'cat_id', 'cat_id');
    }
}
