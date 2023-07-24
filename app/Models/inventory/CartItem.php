<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class CartItem extends Model
    {
        use HasFactory;

        protected $fillable = [ 'sale_id', 'quantity', 'productID', 'total' ];
        protected $hidden   = [ 'updated_at', 'created_at' ];
    }
