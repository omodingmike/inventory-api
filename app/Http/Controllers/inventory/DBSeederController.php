<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Artisan;

    class DBSeederController extends Controller
    {
        public function seed ()
        {
//            Artisan ::call( 'migrate:fresh' );
            Artisan ::call( 'db:seed' );
        }
    }
