<?php

    namespace App\Models\inventory;

    use Aws\S3\S3Client;
    use Exception;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Product extends Model
    {
        use HasFactory;

//    protected $dateFormat = 'd-m-Y';

        protected $fillable = [
            'name' , 'code' , 'category' , 'sub_category' , 'discount' , 'retail_price' , 'purchase_price' , 'whole_sale_price' , 'photo' , 'quantity' , 'units' , 'supplier' , 'sold' , 'user_id' , 'balance'
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

        public function getDiscountAttribute ( $value )
        {
            if ( $value ) {
                return round( $value , 1 );
            }
            return null;
        }

        public function getPhotoAttribute ( $value )
        {
            if ( $value ) {
                try {
                    $awsConfig = [
                        'version' => '2006-03-01' ,
                        'region'  => 'us-east-1' ,
                    ];
                    $s3Client  = new S3Client( $awsConfig );
                    $cmd       = $s3Client -> getCommand( 'GetObject' , [
                        'Bucket' => env( 'AWS_BUCKET' ) ,
                        'Key'    => $value ,
                    ] );
                    $request   = $s3Client -> createPresignedRequest( $cmd , '+480 minutes' );
                    return (string) $request -> getUri();
                }
                catch ( Exception $e ) {
                    return null;
                }
            }
            return null;
        }

        public function getBalanceAttribute ( $value )
        {
            if ( $value ) {
                return $this -> attributes[ 'balance' ] = $this -> attributes[ 'quantity' ] * $this -> attributes[ 'retail_price' ];
            }
            return null;
        }

        public function category () : BelongsTo
        {
            return $this -> belongsTo( Category::class , 'category' , 'id' );
        }


        public function subCategory () : BelongsTo
        {
            return $this -> belongsTo( SubCategory::class , 'sub_category' , 'id' );
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
