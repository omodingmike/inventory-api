<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateProductsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_products' , function ( Blueprint $table ) {
                $table -> id();
                $table -> string( 'name' );
                $table -> integer( 'user_id' );
                $table -> integer( 'category' );
                $table -> integer( 'sub_category' );
                $table -> string( 'code' );
                $table -> string( 'photo' );
                $table -> integer( 'quantity' ) -> default( 0 );
                $table -> integer( 'sold' ) -> default( 0 );
                $table -> integer( 'units' );
                $table -> integer( 'supplier' );
                $table -> integer( 'retail_price' );
                $table -> integer( 'whole_sale_price' );
                $table -> integer( 'purchase_price' );
                $table -> integer( 'balance' );
                $table -> decimal( 'discount' );
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
            Schema ::dropIfExists( 'inv_products' );
        }

    }
