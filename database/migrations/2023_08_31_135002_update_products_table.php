<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class UpdateProductsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::table( 'inv_products' , function ( Blueprint $table ) {
                $table -> renameColumn( 'productName' , 'name' );
                $table -> renameColumn( 'productCategory' , 'category' );
                $table -> renameColumn( 'productSubCategory' , 'sub_category' );
                $table -> renameColumn( 'productCode' , 'code' );
                $table -> renameColumn( 'retailPrice' , 'retail_price' );
                $table -> renameColumn( 'wholeSalePrice' , 'whole_sale_price' );
                $table -> renameColumn( 'purchasePrice' , 'purchase_price' );
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema ::table( 'inv_products' , function ( Blueprint $table ) {
                $table -> renameColumn( 'name' , 'productName' );
                $table -> renameColumn( 'category' , 'productCategory' );
                $table -> renameColumn( 'sub_category' , 'productSubCategory' );
                $table -> renameColumn( 'code' , 'productCode' );
                $table -> renameColumn( 'retail_price' , 'retailPrice' );
                $table -> renameColumn( 'retail_price' , 'retailPrice' );
                $table -> renameColumn( 'whole_sale_price' , 'wholeSalePrice' );
                $table -> renameColumn( 'purchase_price' , 'purchasePrice' );
            } );
        }
    }
