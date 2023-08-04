<?php

    namespace Database\Seeders;

    use App\Models\inventory\CartItem;
    use App\Models\inventory\Category;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Models\inventory\SubCategory;
    use App\Models\inventory\Supplier;
    use App\Models\inventory\Unit;
    use App\Models\User;
    use Illuminate\Database\Seeder;

    class InventorySeeder1 extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run ()
        {
            $user = User ::find( 1 );
            $user -> products() -> saveMany( Product ::factory( 10 ) -> create() );
            $user -> sales() -> saveMany( Sale ::factory( 50 ) -> create() );
            $user -> expenses() -> saveMany( Sale ::factory( 50 ) -> create() );
            $user -> contacts() -> saveMany( Sale ::factory( 10 ) -> create() );

            Category ::factory() -> count( 10 ) -> create();
            SubCategory ::factory() -> count( 10 ) -> create();
            Supplier ::factory() -> count( 10 ) -> create();
            Unit ::factory() -> count( 10 ) -> create();
            CartItem ::factory() -> count( 200 ) -> create();
        }
    }
