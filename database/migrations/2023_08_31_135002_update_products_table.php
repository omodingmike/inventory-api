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
                $table -> integer( 'category' );
                $table -> integer( 'sub_category' );
                $table -> string( 'code' );
                $table -> integer( 'retail_price' );
                $table -> integer( 'whole_sale_price' );
                $table -> integer( 'purchase_price' );
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
                $table -> integer( 'category' );
                $table -> integer( 'sub_category' );
                $table -> string( 'code' );
                $table -> integer( 'retail_price' );
                $table -> integer( 'whole_sale_price' );
                $table -> integer( 'purchase_price' );
            } );
        }
    }
