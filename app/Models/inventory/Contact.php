<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Contact extends Model
    {
        use HasFactory;

        protected $fillable = [ 'name', 'phone', 'email' ];
        protected $hidden   = [ 'created_at', 'updated_at' ];
        protected $table    = 'inv_contacts';

        public function sales () : HasMany
        {
            return $this -> hasMany( Sale::class, 'contact_id', 'id' );
        }
    }
