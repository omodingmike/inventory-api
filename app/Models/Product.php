<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'category_id', 'sub_category', 'discount', 'other_price', 'sale_price', 'wholesale_price', 'photo', 'stock', 'unit_id', 'supplier_id'
    ];
}
