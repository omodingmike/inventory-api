<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateCartItemsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_cart_items', function (Blueprint $table) {
                $table -> id();
                $table -> integer( 'sale_id' );
                $table -> integer( 'productID' );
                $table -> integer( 'quantity' );
                $table -> integer( 'total' );
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
            Schema ::dropIfExists( 'cart_items' );
        }
    }
