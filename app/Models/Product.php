<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    use HasFactory;

    public $fillable = [
        'product_name',
        'description',
        'style',
        'brand',
        'product_type',
        'shipping_price',
        'note',
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
}