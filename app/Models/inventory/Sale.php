<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class Sale extends Model
    {
        use HasFactory;

        protected $fillable = [ 'sale_id' , 'mode' , 'grand_total' , 'contact_id' , 'user_id' ];
        protected $hidden   = [ 'updated_at' , 'contact_id' , 'user_id' ];
        protected $table    = 'inv_sales';

        public static function validateSaleId ( Request $request ) : ?string
        {
            $validator = Validator ::make( $request -> all() ,
                [
                    'sale_id' => 'bail|required|int|exists:inv_sales,id'
                ]
            );
            $errors    = null;
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                $errors = $validator -> messages() -> first();
            }
            return $errors;
        }

        public function getCreatedAtAttribute ( $value )
        {
            if ( $value ) {
//                return date( 'd-m-Y h:m a' , strtotime( $value ) );
                return date( 'jS M Y h:ia' , strtotime( $value ) );
            }
            return null;
        }

        public function customer () : BelongsTo
        {
            return $this -> belongsTo( Contact::class , 'contact_id' , 'id' );
        }

        public function saleItems () : HasMany
        {
            return $this -> hasMany( CartItem::class , 'sale_id' , 'id' );
        }

        public function scopeOfUserID ( $query , $user_id )
        {
            return $query -> where( 'user_id' , $user_id );
        }

        public function scopeDuration ( $query , $start_date , $end_date )
        {
            return $query -> whereBetween( 'created_at' , [ $start_date , $end_date ] );
        }
    }
