<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class CartItem extends Model
    {
        use HasFactory;

        protected $fillable = [ 'sale_id' , 'quantity' , 'productID' , 'total' ];
        protected $hidden   = [ 'updated_at' , 'created_at' ];
        protected $table    = 'inv_cart_items';

        public function sale () : BelongsTo
        {
            return $this -> belongsTo( Sale::class , 'sale_id' );
        }

        public function product () : BelongsTo
        {
            return $this -> belongsTo( Product::class , 'productID' , 'id' );
        }
    }
