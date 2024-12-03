<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'contact_info'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
