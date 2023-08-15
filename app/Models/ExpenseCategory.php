<?php

    namespace App\Models;

    use App\Models\inventory\Expense;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class ExpenseCategory extends Model
    {
        use HasFactory;

        protected $hidden   = [ 'created_at' , 'updated_at' , 'user_id' ];
        protected $fillable = [ 'name' , 'user_id' ];

        public function scopeOfUserID ( $query , $user_id )
        {
            return $query -> where( 'user_id' , $user_id );
        }

        public function expenses () : HasMany
        {
            return $this -> hasMany( Expense::class , 'expense_id' , 'id' );
        }
    }
