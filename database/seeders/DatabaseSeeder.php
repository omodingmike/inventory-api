<?php

    namespace Database\Seeders;

    use App\Models\inventory\CartItem;
    use App\Models\inventory\Category;
    use App\Models\inventory\Contact;
    use App\Models\inventory\Expense;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Models\inventory\SubCategory;
    use App\Models\inventory\Supplier;
    use App\Models\inventory\Unit;
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
//            User ::factory()
//                 -> hasProducts( 10 )
//                 -> hasSales( 50 )
//                 -> hasExpenses( 50 )
//                 -> hasContacts( 10 )
//                 -> count( 50 )
//                 -> create();


            Product ::factory()
                    -> count( 10 )
                    -> create();
            Contact ::factory()
                    -> count( 10 )
                    -> create();
            Sale ::factory()
                 -> count( 50 )
                 -> create();
            Expense ::factory()
                    -> count( 50 )
                    -> create();
            Category ::factory()
                     -> count( 10 )
                     -> create();
            SubCategory ::factory()
                        -> count( 10 )
                        -> create();
            Supplier ::factory()
                     -> count( 10 )
                     -> create();
            Unit ::factory()
                 -> count( 10 )
                 -> create();
            CartItem ::factory()
                     -> count( 200 )
                     -> create();
        }
    }
