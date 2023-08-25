<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreExpenseCategoryRequest;
    use App\Http\Requests\UpdateExpenseCategoryRequest;
    use App\Models\ExpenseCategory;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class ExpenseCategoryController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return array
         */
        public function index ( Request $request )
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => ExpenseCategory ::ofUserID( $request -> user_id ) -> get()
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

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */
        public function create ()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreExpenseCategoryRequest $request
         * @return Response
         */
        public function store ( StoreExpenseCategoryRequest $request )
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param ExpenseCategory $expenseCategory
         * @return Response
         */
        public function show ( ExpenseCategory $expenseCategory )
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param ExpenseCategory $expenseCategory
         * @return Response
         */
        public function edit ( ExpenseCategory $expenseCategory )
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param UpdateExpenseCategoryRequest $request
         * @param ExpenseCategory              $expenseCategory
         * @return Response
         */
        public function update ( UpdateExpenseCategoryRequest $request , ExpenseCategory $expenseCategory )
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param ExpenseCategory $expenseCategory
         * @return Response
         */
        public function destroy ( ExpenseCategory $expenseCategory )
        {
            //
        }
    }
