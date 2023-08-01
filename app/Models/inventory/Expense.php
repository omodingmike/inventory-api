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
    }
