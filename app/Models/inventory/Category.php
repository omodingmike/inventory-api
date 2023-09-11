<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Category extends Model
    {
        use HasFactory;

        
        protected $fillable = [ 'name' , 'photo' , 'user_id' , 'description' ];
        protected $hidden   = [ 'created_at' , 'updated_at' ];
        protected $table    = 'inv_categories';

        public function products () : HasMany
        {
            return $this -> hasMany( Product::class , 'category' , 'id' );
        }

        public function scopeOfUserID ( $query , $user_id )
        {
            return $query -> where( 'user_id' , $user_id );
        }
    }
