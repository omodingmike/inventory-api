<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Expense;
    use App\Models\inventory\ExpenseCategory;
    use App\Models\inventory\Sale;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Collection;


    class ExpenseController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param Request $request
         * @return array
         */
        public function index ( Request $request )
        {
            try {
                $user_id                   = $request -> user_id;
                $start_date                = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
                $end_date                  = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();
                $previous_month_start_date = $start_date -> copy() -> subMonth();
                $previous_month_end_date   = $previous_month_start_date -> copy() -> endOfMonth();

                $previous_month_total_expenses = (int) Expense :: ofUserID( $user_id )
                                                               -> duration( $previous_month_start_date , $previous_month_end_date )
                                                               -> sum( 'amount' );
                $previous_month_total_sales    = (int) Sale :: ofUserID( $user_id )
                                                            -> duration( $previous_month_start_date , $previous_month_end_date )
                                                            -> sum( 'grand_total' );
                $this_month_total_sales        = (int) Sale :: ofUserID( $user_id )
                                                            -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                                            -> sum( 'grand_total' );

                $expenses = Expense :: ofUserID( $user_id )
                                    -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                    -> with( 'expenseCategory' )
//                                    -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS amount' )
//                                    -> groupBy( 'day' , 'cdate' )
                                    -> orderBy( 'date' )
                                    -> get();

                $this_month_total_expenditure = 0;
                $expense_data                 = [];
                foreach ( $expenses as $expense ) {
                    $item[ 'id' ]                 = $expense -> id;
                    $item[ 'name' ]               = $expense -> expenseCategory -> name;
                    $item[ 'amount' ]             = $expense -> amount;
                    $item[ 'date' ]               = $expense -> date;
                    $this_month_total_expenditure += $expense -> amount;
                    $expense_data[]               = $item;
                }
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'expense_percentage' => $previous_month_total_expenses == 0 ? 0 : number_format( ( ( $this_month_total_expenditure - $previous_month_total_expenses ) / $previous_month_total_expenses ) * 100 , 1 ) ,
                        'income_percentage'  => $previous_month_total_sales == 0 ? 0 : number_format( ( ( $this_month_total_sales - $previous_month_total_sales ) / $previous_month_total_sales ) * 100 , 1 ) ,
                        'expenses'           => $expense_data
                    ]
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

        public function expenseCategories ( Request $request )
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Expense ::ofUserID( $request -> user_id )
                                         -> get( [ 'id' , 'name' ] )

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
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store ( Request $request )
        {
            try {
                $validated        = $request -> validate(
                    [ 'name'    => 'required' ,
                      'amount'  => 'required' ,
                      'date'    => 'required' ,
                      'user_id' => 'required'
                    ] );
                $expense_category = ExpenseCategory ::where( 'name' , $validated[ 'name' ] ) -> first();
                if ( $expense_category ) {
                    $validated[ 'expense_id' ] = $expense_category -> id;
                } else {
                    $validated[ 'expense_id' ] = ( ExpenseCategory ::create( $validated ) ) -> id;
                }
                $validated[ 'date' ] = date( 'Y-m-d' , strtotime( $request -> date ) );
                unset( $validated[ 'name' ] );

                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Expense ::create( $validated )
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

        public function expensesAndIncomes ( Request $request )
        {
            $user_id = $request -> user_id;
            try {
                $start_date = Carbon ::parse( $request -> query( 'from' ) ) -> copy() -> startOfDay();
                $end_date   = Carbon ::parse( $request -> query( 'to' ) ) -> copy() -> endOfDay();
//                $expenses                   = new Collection();
//                $top_out_flow               = new Collection();
                $top_in_flow         = new Collection();
                $expenses_statistics = new Collection();
                $difference_in_days  = $start_date -> diffInDays( $end_date );
//                $difference_in_months       = $start_date -> diffInMonths( $end_date );
//                $include_expense_statistics = false;

                if ( $difference_in_days <= 7 ) {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS amount' )
                                        -> groupBy( 'day' , 'cdate' )
                                        -> orderBy( 'cdate' )
                                        -> get();

                } elseif ( $difference_in_days >= 28 && $difference_in_days <= 31 ) {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS amount' )
                                        -> groupBy( 'day' , 'cdate' )
                                        -> orderBy( 'cdate' )
                                        -> get();

                    $expenses_statistics = Expense :: ofUserID( $user_id )
                                                   -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                                   -> selectRaw( 'id, SUM(amount) AS amount' )
                                                   -> groupBy( 'id' )
                                                   -> get();
                } else {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'YEAR(date) AS year, SUM(amount) AS amount' )
                                        -> groupBy( 'year' )
                                        -> orderBy( 'year' )
                                        -> get();
                }

                $top_out_flow = Expense ::ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> orderBy( 'amount' , 'desc' )
                                        -> limit( 2 )
                                        -> get( [ 'id' , 'amount' , 'date' ] );

                $sales = Sale ::ofUserID( $user_id )
                              -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                              -> with( 'saleItems.product.productCategory' )
                              -> orderBy( 'grand_total' , 'desc' )
                              -> limit( 2 )
                              -> get();

                $total_expenses = 0;
                foreach ( $expenses as $expense ) {
                    $total_expenses += $expense -> amount;
                }
                foreach ( $sales as $sale ) {
                    if ( count( $sale -> saleItems ) > 0 ) {
                        $top_in_flow -> push(
                            [
                                'name'       => ( $sale -> saleItems )[ 0 ] -> product -> productCategory() -> first() -> name ,
                                'amount'     => $sale -> grand_total ,
                                'created_at' => $sale -> created_at
                            ] );
                    }
                }
//                }
                $data = [
                    'total_expenses' => $total_expenses ,
                    'chart_data'     => $expenses ,
                    'top_out_flow'   => $top_out_flow ,
                    'top_in_flow'    => $top_in_flow ,
                ];

                if ( count( $expenses_statistics ) > 1 ) {
                    $data[ 'expenses_chart' ] = $expenses_statistics;
                }

                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $data ,
                ];
            }
            catch
            ( Exception $exception ) {
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

