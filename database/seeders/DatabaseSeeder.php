<?php

    namespace Database\Seeders;

    use App\Models\inventory\Category;
    use App\Models\inventory\ExpenseCategory;
    use App\Models\inventory\SubCategory;
    use App\Models\inventory\Supplier;
    use App\Models\inventory\Unit;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        public function run ()
        {
            $units = [
                [ 'name' => 'Pieces' , 'symbol' => 'Pcs' ] ,
                [ 'name' => 'Grams' , 'symbol' => 'g' ] ,
                [ 'name' => 'Liters' , 'symbol' => 'L' ] ,
                [ 'name' => 'Kilograms' , 'symbol' => 'kg' ] ,
                [ 'name' => 'Boxes' , 'symbol' => 'Bxs' ]
            ];
            foreach ( $units as $unit ) {
                Unit ::create( $unit );
            }
            $suppliers = [
                [ 'name' => 'Bukoola Chemical Industries Ltd' , 'photo' => 'inv_images/1699355586_inventory_1.png' ] ,
                [ 'name' => 'Jubaili Agrosciences' , 'photo' => 'inv_images/1699353994_inventory_1.png' ] ,
                [ 'name' => 'Nsanja Agrochemicals' , 'photo' => 'inv_images/1699354039_inventory_2.png' ] ,
                [ 'name' => 'Osho Chemicals Uganda Limited' , 'photo' => 'inv_images/1699354056_Inventory_3.png' ] ,
                [ 'name' => 'Agroscrope Limited' , 'photo' => 'inv_images/1699354073_inventory_4.png' ] ,
                [ 'name' => 'MTK Uganda' , 'photo' => 'inv_images/1699354091_Inventory_5.png' ] ,
                [ 'name' => 'Rainbow AgroSciences' , 'photo' => 'inv_images/1699354108_Inventory_6.png' ] ,
                [ 'name' => 'NASECO Seeds' , 'photo' => 'inv_images/1699354126_inventory_7.png' ] ,
                [ 'name' => 'East African Seeds' , 'photo' => 'inv_images/1699354142_inventory_8.png' ] ,
                [ 'name' => 'Nsanja Seeds' , 'photo' => 'inv_images/1699354161_inventory_9.png' ] ,
                [ 'name' => 'Victoria Seeds' , 'photo' => 'inv_images/1699354178_inventory_10.png' ] ,
                [ 'name' => 'Farm Inputs Care Centre' , 'photo' => 'inv_images/1699353994_inventory_1.png' ] ,
                [ 'name' => 'Grow More Seeds & Chemicals' , 'photo' => 'inv_images/1699354039_inventory_2.png' ] ,
                [ 'name' => 'Simlaw Seeds Company' , 'photo' => 'inv_images/1699354056_Inventory_3.png' ] ,
                [ 'name' => 'Seed Co' , 'photo' => 'inv_images/1699354073_inventory_4.png' ] ,
                [ 'name' => 'VermiPro' , 'photo' => 'inv_images/1699354091_Inventory_5.png' ] ,
            ];
            foreach ( $suppliers as $supplier ) {
                Supplier ::create( $supplier );
            }
            $categories = [
                [ 'name' => 'Fertilizers' , 'description' => 'For fertilizers' , 'photo' => 'inv_images/1699285068_Fertilizers.png' ] ,
                [ 'name' => 'Pesticides' , 'description' => 'For pesticides' , 'photo' => 'inv_images/1699285168_Pesticides.png' ] ,
                [ 'name' => 'Seeds' , 'description' => 'For seeds' , 'photo' => 'inv_images/1699353463_Seeds.png' ] ,
                [ 'name' => 'Farm Tools' , 'description' => 'farm tools' , 'photo' => 'inv_images/1699285205_rake_704128.png' ] ,
                [ 'name' => 'Biologicals' , 'description' => 'for biologicals' , 'photo' => 'inv_images/1699353379_Biologicals.png' ] ,
                [ 'name' => 'Livestock Inputs' , 'description' => 'For livestock' , 'photo' => 'inv_images/1699353487_Veterinary_Inputs.png' ] ,
                [ 'name' => 'Irrigation Supplies' , 'description' => 'Water sprinklers' , 'photo' => 'inv_images/1699353401_Irrigation_Supplies.png' ] ,
                [ 'name' => 'Animal Feeds' , 'description' => 'food for commercial livestock' , 'photo' => 'inv_images/1699353314_Animal_Feeds.png' ] ,
                [ 'name' => 'Machinery' , 'description' => 'Machinery' , 'photo' => 'inv_images/1699353422_Machinery.png' ] ,
                [ 'name' => 'Planting Material' , 'description' => 'Planting materials' , 'photo' => 'inv_images/1699353442_Planting_Materials.png' ] ,
                [ 'name' => 'Herbicides' , 'description' => 'test description' , 'photo' => 'inv_images/1699285168_Pesticides.png' ] ,
            ];

            foreach ( $categories as $category ) {
                Category ::create( $category );
            }

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
                [ 'name' => 'Micronutrients' , 'category_id' => 8 ] ,
                [ 'name' => 'Concentrates' , 'category_id' => 8 ] ,
                [ 'name' => 'Roughages' , 'category_id' => 8 ] ,
                [ 'name' => 'Mineral mixtures' , 'category_id' => 8 ] ,
                [ 'name' => 'Vitamins and supplements' , 'category_id' => 8 ] ,
                [ 'name' => 'Feed additives' , 'category_id' => 9 ] ,
                [ 'name' => 'Tractors and power tillers' , 'category_id' => 9 ] ,
                [ 'name' => 'Planters and seed drills' , 'category_id' => 9 ] ,
                [ 'name' => 'Harvesters and threshers' , 'category_id' => 9 ] ,
                [ 'name' => 'Irrigation equipment' , 'category_id' => 9 ] ,
                [ 'name' => 'Post-harvest equipment' , 'category_id' => 7 ] ,
                [ 'name' => 'Drip irrigation systems' , 'category_id' => 7 ] ,
                [ 'name' => 'Sprinkler systems' , 'category_id' => 7 ] ,
                [ 'name' => 'Pumps and power sources' , 'category_id' => 7 ] ,
                [ 'name' => 'Pipes and fittings' , 'category_id' => 4 ] ,
                [ 'name' => 'Hand tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Power tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Planting tools' , 'category_id' => 4 ] ,
                [ 'name' => 'Pruning tools' , 'category_id' => 5 ] ,
                [ 'name' => 'Biopesticides' , 'category_id' => 5 ] ,
                [ 'name' => 'Biofertilizers' , 'category_id' => 5 ] ,
                [ 'name' => 'Biostimulants' , 'category_id' => 6 ] ,
                [ 'name' => 'Veterinary medicines' , 'category_id' => 6 ] ,
                [ 'name' => 'Breeding materials' , 'category_id' => 6 ] ,
                [ 'name' => 'Housing equipment' , 'category_id' => 3 ] ,
                [ 'name' => 'Hybrid seeds' , 'category_id' => 3 ] ,
                [ 'name' => 'Open-pollinated seeds' , 'category_id' => 3 ] ,
                [ 'name' => 'Genetically modified seeds' , 'category_id' => 10 ] ,
                [ 'name' => 'Seedlings' , 'category_id' => 10 ] ,
                [ 'name' => 'Cuttings' , 'category_id' => 10 ] ,
                [ 'name' => 'Tissue-cultured plants' , 'category_id' => 10 ] ,
                [ 'name' => 'Grafts and budwood' , 'category_id' => 10 ] ,
                [ 'name' => 'Bulbs and tubers' , 'category_id' => 11 ]
            ];


            foreach ( $subcategories as $subcategory ) {
                SubCategory ::create( $subcategory );
            }
            $expensecategories = [
                [ 'name' => 'Cost of Goods' ] ,
                [ 'name' => 'Payroll Expenses' ] ,
                [ 'name' => 'Rent' ] ,
                [ 'name' => 'Utilities' ] ,
                [ 'name' => 'Marketing' ] ,
                [ 'name' => 'Taxes' ] ,
                [ 'name' => 'Office Supplies' ] ,
                [ 'name' => 'Repairs' ] ,
                [ 'name' => 'Interest on Loan' ] ,
                [ 'name' => 'Subscriptions' ] ,
                [ 'name' => 'Others' ]
            ];
            foreach ( $expensecategories as $expensecategory ) {
                ExpenseCategory ::create( $expensecategory );
            }
        }
    }
