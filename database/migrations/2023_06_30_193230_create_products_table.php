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
                $table -> string( 'productName' );
                $table -> integer( 'user_id' );
                $table -> integer( 'productCategory' );
                $table -> integer( 'productSubCategory' );
                $table -> string( 'productCode' );
                $table -> string( 'photo' );
                $table -> integer( 'quantity' );
                $table -> integer( 'units' );
                $table -> integer( 'supplier' );
                $table -> integer( 'retailPrice' );
                $table -> integer( 'wholeSalePrice' );
                $table -> integer( 'purchasePrice' );
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
            Schema ::dropIfExists( 'products' );
        }

    }
