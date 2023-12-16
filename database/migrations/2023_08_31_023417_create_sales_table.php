<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSalesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_sales' , function ( Blueprint $table ) {
                $table -> id();
                $table -> string( 'sale_id' );
                $table -> string( 'mode' );
                $table -> integer( 'grand_total' );
                $table -> integer( 'contact_id' );
                $table -> integer( 'user_id' );
                $table -> integer( 'discount' ) -> default( 0 );
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
            Schema ::dropIfExists( 'inv_sales' );
        }
    }
