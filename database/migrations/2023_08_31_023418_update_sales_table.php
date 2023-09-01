<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class UpdateSalesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::table( 'inv_sales' , function ( Blueprint $table ) {
                $table -> renameColumn( 'mode' , 'payment_mode' );
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema ::table( 'inv_sales' , function ( Blueprint $table ) {
                $table -> renameColumn( 'payment_mode' , 'mode' );
            } );
        }
    }
