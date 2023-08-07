<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Contact extends Model
    {
        use HasFactory;

        protected $fillable = [ 'name' , 'phone' , 'email' , 'user_id' ];
        protected $hidden   = [ 'created_at' , 'updated_at' , 'user_id' ];
        protected $table    = 'inv_contacts';

        public function sales () : HasMany
        {
            return $this -> hasMany( Sale::class , 'contact_id' , 'id' );
        }

        public function scopeOfUserID ( $query , $user_id )
        {
            return $query -> where( 'user_id' , $user_id );
        }
    }
