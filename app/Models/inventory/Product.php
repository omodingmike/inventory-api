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
            'productName' , 'productCode' , 'productCategory' , 'productSubCategory' , 'discount' , 'retailPrice' , 'purchasePrice' , 'wholeSalePrice' , 'photo' , 'quantity' , 'units' , 'supplier' , 'sold'
        ];
        protected $hidden   = [ 'updated_at' ];
        protected $table    = 'inv_products';


        public function getCreatedAtAttribute ( $value )
        {
            if ( $value ) {
                return date( 'd-m-Y' , strtotime( $value ) );
            }
            return null;
        }

//        public function getUnitsAttribute ( $value )
//        {
//            if ( $value ) {
//                return $this -> attributes[ 'units' ] = $this -> unit -> name;
//            }
//            return null;
//        }

        public function getBalanceAttribute ( $value )
        {
            if ( $value ) {
                return $this -> attributes[ 'balance' ] = $this -> attributes[ 'quantity' ] * $this -> attributes[ 'retailPrice' ];
            }
            return null;
        }

        public function productCategory () : BelongsTo
        {
            return $this -> belongsTo( Category::class , 'productCategory' , 'id' );
        }


        public function productSubCategory () : BelongsTo
        {
            return $this -> belongsTo( SubCategory::class , 'productSubCategory' , 'id' );
        }


        public function supplier () : BelongsTo
        {
            return $this -> belongsTo( Supplier::class , 'supplier' , 'id' );
        }

        public function units () : BelongsTo
        {
            return $this -> belongsTo( Unit::class , 'units' , 'id' );
        }

        public function scopeOfUserID ( $query , $user_id )
        {
            return $query -> where( 'user_id' , $user_id );
        }

        public function scopeOfID ( $query , $id )
        {
            return $query -> where( 'id' , $id );
        }

        public function scopeDuration ( $query , $start_date , $end_date )
        {
            return $query -> whereBetween( 'created_at' , [ $start_date , $end_date ] );
        }
    }
