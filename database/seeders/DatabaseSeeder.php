<?php

    namespace Database\Seeders;

    use App\Models\inventory\CartItem;
    use App\Models\inventory\Category;
    use App\Models\inventory\Contact;
    use App\Models\inventory\Expense;
    use App\Models\inventory\ExpenseCategory;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Models\inventory\SubCategory;
    use App\Models\inventory\Supplier;
    use App\Models\inventory\Unit;
    use App\Models\User;
    use Illuminate\Database\Seeder;

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run ()
        {
            User ::factory() -> count( 100 ) -> create();
            Product ::factory() -> count( 1000 ) -> create();
            Sale ::factory() -> count( 1000 ) -> create();
            Expense ::factory() -> count( 1000 ) -> create();
            Contact ::factory() -> count( 100 ) -> create();

            Category ::factory() -> count( 100 ) -> create();
            SubCategory ::factory() -> count( 100 ) -> create();
            ExpenseCategory ::factory() -> count( 100 ) -> create();
            Supplier ::factory() -> count( 100 ) -> create();
            Unit ::factory() -> count( 100 ) -> create();
            CartItem ::factory() -> count( 1000 ) -> create();
        }
    }
