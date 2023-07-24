<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Product extends Model
    {
        use HasFactory;

//    protected $dateFormat = 'd-m-Y';

        protected $fillable = [
            'productName', 'productCode', 'productCategory', 'productSubCategory', 'discount', 'retailPrice', 'purchasePrice', 'wholeSalePrice', 'photo', 'quantity', 'units', 'supplier',
        ];
        protected $hidden   = [ 'updated_at' ];
        protected $table    = 'inv_products';


        public function getCreatedAtAttribute ($value)
        {
            if ( $value ) {
                return date( 'd-m-Y', strtotime( $value ) );
            }
            return null;
        }

        public function productCategory () : BelongsTo
        {
            return $this -> belongsTo( Category::class, 'productCategory', 'id' );
        }

        public function productSubCategory () : BelongsTo
        {
            return $this -> belongsTo( Category::class, 'productSubCategory', 'id' );
        }


        public function supplier () : BelongsTo
        {
            return $this -> belongsTo( Supplier::class, 'supplier', 'id' );
        }

        public function units () : BelongsTo
        {
            return $this -> belongsTo( Unit::class, 'units', 'id' );
        }
    }