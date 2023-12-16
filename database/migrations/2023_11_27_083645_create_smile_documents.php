<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSmileDocuments extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::create( 'smile_documents' , function ( Blueprint $table ) {
                $table -> id();
                $table -> longText( 'selfie' );
                $table -> longText( 'id_front' );
                $table -> longText( 'id_back' );
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
            Schema ::dropIfExists( 'smile_documents' );
        }
    }
