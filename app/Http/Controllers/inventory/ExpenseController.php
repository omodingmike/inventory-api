<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Expense;
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
                $startDate                 = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
                $endDate                   = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();
                $previous_month_start_date = $startDate -> copy() -> subMonth();
                $previous_month_end_date   = $previous_month_start_date -> copy() -> endOfMonth();

                $previous_month_expenses = (int) Expense :: ofUserID( $user_id )
                                                         -> duration( $previous_month_start_date , $previous_month_end_date )
                                                         -> sum( 'amount' );
                $previous_month_sales    = (int) Sale :: ofUserID( $user_id )
                                                      -> duration( $previous_month_start_date , $previous_month_end_date )
                                                      -> sum( 'grand_total' );
                $this_month_sales        = (int) Sale :: ofUserID( $user_id )
                                                      -> duration( $startDate -> copy() -> startOfDay() , $endDate -> copy() -> endOfDay() )
                                                      -> sum( 'grand_total' );

                $expenses = Expense :: ofUserID( $user_id )
                                    -> duration( $startDate -> copy() -> startOfDay() , $endDate -> copy() -> endOfDay() )
                                    -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS amount' )
                                    -> groupBy( 'day' , 'cdate' )
                                    -> orderBy( 'cdate' )
                                    -> get();

                $this_month_total_expenditure = 0;
                foreach ( $expenses as $expense ) {
                    $this_month_total_expenditure += $expense -> amount;
                }

                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'expense_percentage' => $previous_month_expenses == 0 ? 0 : number_format( ( ( $this_month_total_expenditure - $previous_month_expenses ) / $previous_month_expenses ) * 100 , 1 ) ,
                        'income_percentage'  => $previous_month_sales == 0 ? 0 : number_format( ( ( $this_month_sales - $previous_month_sales ) / $previous_month_sales ) * 100 , 1 ) ,
                        'expenses'           => $expenses
                    ]

                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => []
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
            $validated           = $request -> validate(
                [ 'name'    => 'required' ,
                  'amount'  => 'required' ,
                  'date'    => 'required' ,
                  'user_id' => 'required'
                ] );
            $validated[ 'date' ] = date( 'Y-m-d' , strtotime( $request -> date ) );
            $expense             = Expense ::create( $validated );

            if ( $expense ) {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $expense
                ];
            } else {
                return [
                    'status'  => 0 ,
                    'message' => 'Operation failed' ,
                    'data'    => []
                ];
            }
        }

        public function expensesAndIncomes ( Request $request )
        {
            $user_id = $request -> user_id;
            try {
                $start_date                 = Carbon ::parse( $request -> query( 'from' ) ) -> copy() -> startOfDay();
                $end_date                   = Carbon ::parse( $request -> query( 'to' ) ) -> copy() -> endOfDay();
                $expenses                   = new Collection();
                $top_out_flow               = new Collection();
                $top_in_flow                = new Collection();
                $expenses_statistics        = new Collection();
                $difference_in_days         = $start_date -> diffInDays( $end_date );
                $difference_in_months       = $start_date -> diffInMonths( $end_date );
                $include_expense_statistics = false;

//                $temp  = Expense :: ofUserID( $user_id )
//                                 -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                 -> selectRaw( 'MONTHNAME(date) AS month, COUNT(*) AS count, SUM(amount) AS amount' )
//                                 -> groupBy( 'month' )
//                                 -> orderBy( 'count' , 'desc' )
//                                 -> get();
//                $temp2 = Sale :: ofUserID( $user_id )
//                              -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                              -> selectRaw( 'MONTHNAME(created_at) AS month, COUNT(*) AS count, SUM(grand_total) AS amount' )
//                              -> groupBy( 'month' )
//                              -> orderBy( 'count' , 'desc' )
//                              -> get();

                if ( $difference_in_days <= 7 ) {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS amount' )
                                        -> groupBy( 'day' , 'cdate' )
                                        -> orderBy( 'cdate' )
                                        -> get();

                } elseif ( $difference_in_days >= 28 && $difference_in_days <= 31 ) {
                    $expenses            = Expense :: ofUserID( $user_id )
                                                   -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                        -> selectRaw( 'DATE(date) AS week_day, SUM(amount) AS amount' )
//                                        -> groupBy( 'week_day' )
                                                   -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS amount' )
                                                   -> groupBy( 'day' , 'cdate' )
                                                   -> orderBy( 'cdate' )
                                                   -> get();
                    $expenses_statistics = Expense :: ofUserID( $user_id )
                                                   -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                                   -> selectRaw( 'name, SUM(amount) AS amount' )
                                                   -> groupBy( 'name' )
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
                                        -> get( [ 'name' , 'amount' , 'date' ] );

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
                    'message' => $exception -> getTrace() ,
                    'data'    => []
                ];
            }
        }
    }

