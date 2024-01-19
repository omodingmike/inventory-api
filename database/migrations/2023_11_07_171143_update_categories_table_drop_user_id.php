<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class UpdateCategoriesTableDropUserId extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::table( 'inv_categories' , function ( Blueprint $table ) {
                $table -> dropColumn( 'user_id' );
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
                $table -> addColumn( 'integer' , 'user_id' );
            } );
        }
    }