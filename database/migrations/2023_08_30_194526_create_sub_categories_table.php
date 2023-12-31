<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSubCategoriesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_sub_categories' , function ( Blueprint $table ) {
                $table -> id();
                $table -> string( 'name' );
                $table -> integer( 'user_id' );
                $table -> integer( 'category_id' );
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
            Schema ::dropIfExists( 'inv_sub_categories' );
        }
    }
