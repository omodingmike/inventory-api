<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\ExpenseCategory;
    use Exception;
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
            $expense_categories = ExpenseCategory ::ofUserID( $request -> user_id ) -> get();
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $expense_categories
                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => [
                        'file' => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line' => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
                ];
            }
        }
    }
