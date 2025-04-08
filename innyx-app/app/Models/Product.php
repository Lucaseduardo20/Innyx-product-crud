<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'expires_at',
        'image',
        'category_id'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

