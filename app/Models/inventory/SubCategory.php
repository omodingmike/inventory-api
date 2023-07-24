<?php

    namespace App\Models\inventory;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class SubCategory extends Model
    {
        use HasFactory;

        protected $fillable = [ 'name' ];
        protected $table    = 'inv_sub_categories';
        protected $hidden   = [ 'created_at', 'updated_at' ];
    }
