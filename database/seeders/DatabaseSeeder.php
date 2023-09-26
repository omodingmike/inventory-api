<?php

    namespace Database\Seeders;

    use App\Models\inventory\CartItem;
    use App\Models\inventory\Expense;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        public function run ()
        {
//            User ::factory() -> count( 1 ) -> create();
            Product ::factory() -> count( 1000 ) -> create();
            Sale ::factory() -> count( 1000 ) -> create();
            Expense ::factory() -> count( 1000 ) -> create();
            CartItem ::factory() -> count( 1000 ) -> create();

//            Contact ::factory() -> count( 1 ) -> create();
//            Category ::factory() -> count( 10 ) -> create();
//            SubCategory ::factory() -> count( 10 ) -> create();
//            ExpenseCategory ::factory() -> count( 10 ) -> create();
//            Supplier ::factory() -> count( 10 ) -> create();
//            Unit ::factory() -> count( 10 ) -> create();
        }
    }
