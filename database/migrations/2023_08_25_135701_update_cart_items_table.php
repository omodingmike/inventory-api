<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class UpdateCartItemsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::table( 'inv_cart_items' , function ( Blueprint $table ) {
                $table -> renameColumn( 'productID' , 'product_id' );
            } );
        }

        /**c
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema ::create( 'inv_cart_items' , function ( Blueprint $table ) {
                $table -> renameColumn( 'product_id' , 'productID' );
            } );
        }
    }
