<?php

    namespace App\helpers;

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

    class TestingModels
    {
        public static function createModels ()
        {
            User ::factory() -> count( 1 ) -> create();
            Product ::factory() -> count( 1 ) -> create();
            Sale ::factory() -> count( 1 ) -> create();
            Expense ::factory() -> count( 1 ) -> create();
            Contact ::factory() -> count( 1 ) -> create();

            Category ::factory() -> count( 1 ) -> create();
            SubCategory ::factory() -> count( 1 ) -> create();
            ExpenseCategory ::factory() -> count( 1 ) -> create();
            Supplier ::factory() -> count( 1 ) -> create();
            Unit ::factory() -> count( 1 ) -> create();
            CartItem ::factory() -> count( 1 ) -> create();
        }
    }