<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddDiscountToSalesTable extends Migration
    {
        public function up ()
        {
            Schema ::table( 'sales' , function ( Blueprint $table ) {

                $doctrine_schema_manager = Schema ::getConnection() -> getDoctrineSchemaManager();
                $columns                 = $doctrine_schema_manager -> listTableColumns( 'inv_sales' );
                if ( !array_key_exists( 'discount' , $columns ) ) {
                    $table -> integer( 'discount' ) -> default( 0 );
                }
            } );
        }

        public function down () : void
        {
            Schema ::table( 'sales' , function ( Blueprint $table ) {
                $doctrine_schema_manager = Schema ::getConnection() -> getDoctrineSchemaManager();
                $columns                 = $doctrine_schema_manager -> listTableColumns( 'inv_sales' );
                if ( array_key_exists( 'discount' , $columns ) ) {
                    $table -> dropColumn( 'discount' );
                }
            } );
        }
    }
