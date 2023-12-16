<?php

    use App\Models\inventory\SubCategory;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class RecreateSubcateogoriesTableAndSeedData extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema ::dropIfExists( 'inv_sub_categories' );
            Schema ::create( 'inv_sub_categories' , function ( Blueprint $table ) {
                $table -> id();
                $table -> string( 'name' );
                $table -> integer( 'category_id' );
                $table -> timestamps();
            } );
            $subcategories = [
                [ 'name' => 'Herbicides' , 'category_id' => 2 ] ,
                [ 'name' => 'Insecticides' , 'category_id' => 2 ] ,
                [ 'name' => 'Fungicides' , 'category_id' => 2 ] ,
                [ 'name' => 'Rodenticides' , 'category_id' => 2 ] ,
                [ 'name' => 'Molluscicides' , 'category_id' => 2 ] ,
                [ 'name' => 'Nematicides' , 'category_id' => 2 ] ,
                [ 'name' => 'Organic Pesticides' , 'category_id' => 2 ] ,
                [ 'name' => 'Nitrogenous fertilizers' , 'category_id' => 1 ] ,
                [ 'name' => 'Phosphatic fertilizers' , 'category_id' => 1 ] ,
                [ 'name' => 'Potassic fertilizers' , 'category_id' => 1 ] ,
                [ 'name' => 'Compound fertilizers' , 'category_id' => 1 ] ,
                [ 'name' => 'Organic fertilizers' , 'category_id' => 1 ] ,
                [ 'name' => 'Micronutrients' , 'category_id' => 1 ] ,
                [ 'name' => 'Concentrates' , 'category_id' => 8 ] ,
                [ 'name' => 'Roughages' , 'category_id' => 8 ] ,
                [ 'name' => 'Mineral mixtures' , 'category_id' => 8 ] ,
                [ 'name' => 'Vitamins and supplements' , 'category_id' => 8 ] ,
                [ 'name' => 'Feed additives' , 'category_id' => 8 ] ,
                [ 'name' => 'Tractors and power tillers' , 'category_id' => 9 ] ,
                [ 'name' => 'Planters and seed drills' , 'category_id' => 9 ] ,
                [ 'name' => 'Harvesters and threshers' , 'category_id' => 9 ] ,
                [ 'name' => 'Irrigation equipment' , 'category_id' => 9 ] ,
                [ 'name' => 'Post-harvest equipment' , 'category_id' => 9 ] ,
                [ 'name' => 'Drip irrigation systems' , 'category_id' => 7 ] ,
                [ 'name' => 'Sprinkler systems' , 'category_id' => 7 ] ,
                [ 'name' => 'Pumps and power sources' , 'category_id' => 7 ] ,
                [ 'name' => 'Pipes and fittings' , 'category_id' => 7 ] ,
                [ 'name' => 'Hand tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Power tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Planting tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Pruning tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Biopesticides' , 'category_id' => 5 ] ,
                [ 'name' => 'Biofertilizers' , 'category_id' => 5 ] ,
                [ 'name' => 'Biostimulants' , 'category_id' => 5 ] ,
                [ 'name' => 'Veterinary medicines' , 'category_id' => 6 ] ,
                [ 'name' => 'Breeding materials' , 'category_id' => 6 ] ,
                [ 'name' => 'Housing equipment' , 'category_id' => 6 ] ,
                [ 'name' => 'Hybrid seeds' , 'category_id' => 3 ] ,
                [ 'name' => 'Open-pollinated seeds' , 'category_id' => 3 ] ,
                [ 'name' => 'Genetically modified seeds' , 'category_id' => 3 ] ,
                [ 'name' => 'Seedlings' , 'category_id' => 10 ] ,
                [ 'name' => 'Cuttings' , 'category_id' => 10 ] ,
                [ 'name' => 'Tissue-cultured plants' , 'category_id' => 10 ] ,
                [ 'name' => 'Grafts and budwood' , 'category_id' => 10 ] ,
                [ 'name' => 'Bulbs and tubers' , 'category_id' => 10 ]
            ];

            foreach ( $subcategories as $subcategory ) {
                SubCategory ::create( $subcategory );
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down () : void
        {
            Schema ::dropIfExists( 'inv_sub_categories' );
        }
    }
