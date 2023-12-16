<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Models\inventory\ExpenseCategory;
    use App\Traits\UserTrait;
    use Illuminate\Http\Request;

    class ExpenseCategoryController extends Controller
    {
        use UserTrait;

        public function index ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
//            $expense_categories = ExpenseCategory ::ofUserID( $this -> userID( $request ) ) -> get();
            $expense_categories = ExpenseCategory ::all();
            if ( $expense_categories -> count() > 0 ) return Response ::success( $expense_categories );
            else return Response ::error( 'No Expense categories found' );
        }
    }
