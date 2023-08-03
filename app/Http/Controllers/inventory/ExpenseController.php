<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Expense;
    use App\Models\inventory\Sale;
    use App\Models\inventory\User;
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
            $user_id   = $request -> user_id;
            $startDate = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
            $endDate   = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();

            $revenue = Sale ::where( 'user_id' , $user_id )
                            -> whereBetween( 'created_at' , [ $startDate , $endDate ] )
                            -> get();
            $user    = User ::find( $request -> user_id );
            if ( $user ) {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $user -> expenses ];
            } else {
                return [
                    'status'  => 0 ,
                    'message' => 'No expenses found' ,
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
                $start_date         = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
                $end_date           = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();
                $expenses           = new Collection();
                $top_out_flow       = new Collection();
                $top_in_flow        = new Collection();
                $difference_in_days = $start_date -> diffInDays( $end_date );

                if ( $difference_in_days <= 7 ) {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'DAYNAME(date) AS day, SUM(amount) AS amount' )
                                        -> groupBy( 'day' )
                                        -> get();
                } elseif ( $difference_in_days >= 28 && $difference_in_days <= 31 ) {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'DATE(date) AS week_day, SUM(amount) AS amount' )
                                        -> groupBy( 'week_day' )
                                        -> get();
                } //                else ( $start_date -> diffInMonths( $end_date ) > 1 ) {
                else {
                    $expenses = Expense :: ofUserID( $user_id )
                                        -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> selectRaw( 'YEAR(date) AS year, SUM(amount) AS amount' )
                                        -> groupBy( 'year' )
                                        -> get();
                }

                $expenses_statistics = Expense :: ofUserID( $user_id )
                                               -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                               -> selectRaw( 'name, SUM(amount) AS amount' )
                                               -> groupBy( 'name' )
                                               -> get();

//                $expenses = Expense :: ofUserID( $user_id )
//                                    -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                    -> selectRaw( 'DATE(date) AS expense_date, SUM(amount) AS amount' )
//                                    -> groupBy( 'expense_date' )
//                                    -> get();


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

                $total_sales = 0;
                foreach ( $expenses as $expense ) {
                    $total_sales += $expense -> amount;
                }
                foreach ( $sales as $sale ) {
                    $top_in_flow -> push(
                        [
                            'name'       => ( $sale -> saleItems )[ 0 ] -> product -> productCategory() -> first() -> name ,
                            'amount'     => $sale -> grand_total ,
                            'created_at' => $sale -> created_at
                        ] );
                }
//                }

                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'total_sales'    => $total_sales ,
                        'chart_data'     => $expenses ,
                        'top_out_flow'   => $top_out_flow ,
                        'top_in_flow'    => $top_in_flow ,
                        'expenses_chart' => $expenses_statistics
                    ] ,

                ];
            }
            catch
            ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => []
                ];
            }
        }
    }

