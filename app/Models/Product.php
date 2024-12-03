<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'profit_margin_type',
        'profit_margin_value',
        'final_price'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

}
