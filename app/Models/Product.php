<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
