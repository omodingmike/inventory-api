<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateUnitsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_units' , function ( Blueprint $table ) {
                $table -> id();
                $table -> string( 'name' );
                $table -> string( 'symbol' );
                $table -> timestamps();
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema ::dropIfExists( 'inv_units' );
        }
    }
