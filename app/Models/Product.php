<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

//    protected $dateFormat = 'd-m-Y';

    protected $fillable = [
        'name', 'code', 'category_id', 'sub_category', 'discount', 'other_price', 'sale_price', 'wholesale_price', 'photo', 'stock', 'unit_id', 'supplier_id',
    ];

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return date('d-m-Y', strtotime($value));
        }
        return null;
    }

//    public function setCreatedAtAttribute($value)
//    {
//        $this->attributes['created_at'] = date('d-m-Y', strtotime($value));
//    }
}
