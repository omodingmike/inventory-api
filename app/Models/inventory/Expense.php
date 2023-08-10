<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Expense extends Model
    {
        use HasFactory;

        protected $fillable = [ 'amount' , 'name' , 'date' , 'user_id' ];
        protected $hidden   = [ 'created_at' , 'updated_at' ];
        protected $table    = 'inv_expenses';

        public function getDateAttribute ( $value )
        {
            if ( $value ) {
                return date( 'd-m-Y' , strtotime( $value ) );
            }
            return null;
        }

        /**
         * @param $query
         * @param $user_id
         * @return mixed
         */
        public function scopeOfUserID ( $query , $user_id )
        {
            return $query -> where( 'user_id' , $user_id );
        }

        public function scopeDuration ( $query , $start_date , $end_date )
        {
            return $query -> whereBetween( 'date' , [ $start_date , $end_date ] );
        }
    }
