<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Models\inventory\ExpenseCategory;
    use App\Models\User;
    use Illuminate\Http\Request;

    class ExpenseCategoryController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return array
         */
        public function index ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id            = $request -> user_id;
            $expense_categories = ExpenseCategory ::ofUserID( $user_id ) -> get();
            if ( $expense_categories -> count() > 1 ) return Response ::success( $expense_categories );
            else return Response ::error( 'No Expense categories found' );
        }
    }
