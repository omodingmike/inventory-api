<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class MakeEmailNullableInContactsTable extends Migration
    {
        public function up () : void
        {
            Schema ::table( 'inv_contacts' , function ( Blueprint $table ) {
                $table -> string( 'email' ) -> nullable() -> default( null ) -> change();
            } );
        }

        public function down () : void
        {
            Schema ::table( 'inv_contacts' , function ( Blueprint $table ) {
                $table -> string( 'email' ) -> change();
            } );
        }
    }
