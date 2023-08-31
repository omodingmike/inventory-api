<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateExpensesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'inv_expenses' , function ( Blueprint $table ) {
                $table -> id();
                $table -> string( 'expense_id' );
                $table -> integer( 'amount' );
                $table -> integer( 'user_id' );
                $table -> dateTime( 'date' );
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
            Schema ::dropIfExists( 'inv_expenses' );
        }
    }
