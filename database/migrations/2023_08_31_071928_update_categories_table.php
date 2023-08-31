<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class UpdateCategoriesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::table( 'inv_categories' , function ( Blueprint $table ) {
                $table -> addColumn( 'integer' , 'user_id' );
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema ::table( 'inv_categories' , function ( Blueprint $table ) {
                $table -> dropColumn( [ 'user_id' ] );
            } );
        }
    }
