<?php

    namespace Database\Seeders;

    use App\Models\inventory\CartItem;
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

//            User ::factory()
//                 -> has( Product ::factory( 10 )
//                                 -> has( Category ::factory( 1 ) )
//                                 -> has( Supplier ::factory( 1 ) )
//                                 -> has( SubCategory ::factory( 1 ) )
//                                 -> has( Unit ::factory( 1 ) ) )
//                 -> has( Sale ::factory( 10 )
//                              -> has( Contact ::factory( 1 ) )
//                              -> has( CartItem ::factory( 4 ) ) )
//                 -> has( Expense ::factory( 10 ) )
//                 -> has( Contact ::factory( 10 ) )
//                 -> count( 50 )
//                 -> create();


//            $user = User ::find( 427 );
//            $user -> products() -> saveMany( Product ::factory( 10 ) -> create() );
//            $user -> sales() -> saveMany( Sale ::factory( 50 ) -> create() );
//            $user -> expenses() -> saveMany( Sale ::factory( 50 ) -> create() );
//            $user -> contacts() -> saveMany( Sale ::factory( 10 ) -> create() );


//            User ::factory() -> count( 10 ) -> create();
//            Product ::factory() -> count( 1000 ) -> create();
//            Sale ::factory() -> count( 10 ) -> create();
//            Expense ::factory() -> count( 1000 ) -> create();
//            Contact ::factory() -> count( 10 ) -> create();


//            Category ::factory() -> count( 10 ) -> create();
//            SubCategory ::factory() -> count( 10 ) -> create();
//            Supplier ::factory() -> count( 10 ) -> create();
//            Unit ::factory() -> count( 10 ) -> create();
            CartItem ::factory() -> count( 1000 ) -> create();


        }
    }
