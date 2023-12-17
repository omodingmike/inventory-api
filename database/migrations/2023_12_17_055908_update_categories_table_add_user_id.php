<?php

    use App\Traits\TableSchemaTrait;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class UpdateCategoriesTableAddUserId extends Migration
    {
        use TableSchemaTrait;

        public function up ()
        {
            Schema ::table( 'inv_categories' , function ( Blueprint $table ) {
                $table -> integer( 'user_id' ) -> after( 'id' );
            } );
        }

        public function down ()
        {
            $this -> dropColumnIfExists( 'inv_categories' , 'user_id' );
        }
    }
