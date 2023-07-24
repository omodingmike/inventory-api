<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSalesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_sales', function (Blueprint $table) {
                $table -> id();
                $table -> string( 'sale_id' );
                $table -> string( 'mode' );
                $table -> string( 'grand_total' );
                $table -> string( 'contact_id' );
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
            Schema ::dropIfExists( 'sales' );
        }
    }
