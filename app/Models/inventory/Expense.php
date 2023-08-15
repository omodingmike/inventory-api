<?php

    namespace App\Models\inventory;

    use App\Models\ExpenseCategory;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    class Expense extends Model
    {
        use HasFactory;

        protected $fillable = [ 'amount' , 'name' , 'date' , 'user_id' , 'expense_id' ];
        protected $hidden   = [ 'created_at' , 'updated_at' ];
        protected $table    = 'inv_expenses';

        public function getDateAttribute ( $value )
        {
            if ( $value ) {
                return date( 'jS M Y' , strtotime( $value ) );
            }
            return null;
        }

        public function expenseCategory () : BelongsTo
        {
            return $this -> belongsTo( ExpenseCategory::class , 'expense_id' , 'id' );
//            return $this -> belongsTo( ExpenseCategory::class , 'id' , 'expense_id' );
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
