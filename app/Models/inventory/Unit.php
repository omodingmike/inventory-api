<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Unit extends Model
    {
        use HasFactory;

        protected $fillable = [ 'name', 'symbol' ];
        protected $hidden   = [ 'created_at', 'updated_at' ];
        protected $table    = 'inv_units';
    }
