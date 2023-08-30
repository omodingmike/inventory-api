<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreExpenseRequest;
    use App\Models\inventory\Expense;
    use App\Models\inventory\ExpenseCategory;
    use App\Models\inventory\Sale;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Validator;


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
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id              = $request -> user_id;
            $date_range_validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'bail|required|date' ,
                    'to'   => 'bail|required|date' ,
                ]
            );
            if ( $date_range_validator -> fails() ) return Response ::error( $date_range_validator -> errors() -> first() );

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
                                -> orderBy( 'date' )
                                -> get();

            $this_month_total_expenditure = 0;
            $expense_data                 = [];
            if ( $expenses -> count() < 1 ) return Response ::error( "No expenses found" );
            foreach ( $expenses as $expense ) {
                $item[ 'id' ]                 = $expense -> id;
                $item[ 'name' ]               = $expense -> expenseCategory -> name;
                $item[ 'amount' ]             = $expense -> amount;
                $item[ 'date' ]               = $expense -> date;
                $this_month_total_expenditure += $expense -> amount;
                $expense_data[]               = $item;
            }
            $data = [
                'expense_percentage' => $previous_month_total_expenses == 0 ? 0 : number_format( ( ( $this_month_total_expenditure - $previous_month_total_expenses ) / $previous_month_total_expenses ) * 100 , 1 ) ,
                'income_percentage'  => $previous_month_total_sales == 0 ? 0 : number_format( ( ( $this_month_total_sales - $previous_month_total_sales ) / $previous_month_total_sales ) * 100 , 1 ) ,
                'expenses'           => $expense_data
            ];
            return Response ::success( $data );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreExpenseRequest $request
         * @return string[]
         */
        public function store ( StoreExpenseRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated        = $request -> validated();
            $expense_category = ExpenseCategory ::find( $validated[ 'category_id' ] );
            if ( $expense_category ) $validated[ 'expense_id' ] = $expense_category -> id;
            else $validated[ 'expense_id' ] = ( ExpenseCategory ::create( $validated ) ) -> id;
            $validated[ 'date' ] = date( 'Y-m-d' , strtotime( $request -> date ) );
            unset( $validated[ 'name' ] );
            $expense = Expense ::create( $validated );
            if ( $expense ) return Response ::success( $expense );
            else return Response ::error( 'Expense could not be created' );
        }

        public function expensesAndIncomes ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id              = $request -> user_id;
            $date_range_validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'bail|required|date' ,
                    'to'   => 'bail|required|date' ,
                ]
            );
            if ( $date_range_validator -> fails() ) return Response ::error( $date_range_validator -> errors() -> first() );
            $start_date          = Carbon ::parse( $request -> query( 'from' ) ) -> copy() -> startOfDay();
            $end_date            = Carbon ::parse( $request -> query( 'to' ) ) -> copy() -> endOfDay();
            $top_in_flow         = new Collection();
            $expenses_statistics = new Collection();
            $difference_in_days  = $start_date -> diffInDays( $end_date );

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
                if ( $expenses -> count() < 1 ) return Response ::error( 'No expenses found' );
            }

            $top_out_flow = Expense ::ofUserID( $user_id )
                                    -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                    -> orderBy( 'amount' , 'desc' )
                                    -> limit( 2 )
                                    -> get( [ 'id' , 'amount' , 'date' ] );

            $sales = Sale ::ofUserID( $user_id )
                          -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                          -> with( 'saleItems.product.category' )
                          -> orderBy( 'grand_total' , 'desc' )
                          -> limit( 2 )
                          -> get();
            if ( $sales -> count() < 1 ) return Response ::error( 'No sales found' );

            $total_expenses = 0;
            foreach ( $expenses as $expense ) {
                $total_expenses += $expense -> amount;
            }
            foreach ( $sales as $sale ) {
                if ( count( $sale -> saleItems ) > 0 ) {
                    $top_in_flow -> push(
                        [
                            'name'       => ( $sale -> saleItems )[ 0 ] -> product -> category() -> first() -> name ,
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
            return Response ::success( $data );
        }
    }

