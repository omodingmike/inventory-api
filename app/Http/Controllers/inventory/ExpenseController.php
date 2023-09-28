<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreExpenseRequest;
    use App\Models\inventory\Expense;
    use App\Models\inventory\ExpenseCategory;
    use App\Models\inventory\Sale;
    use App\Traits\DateTrait;
    use App\Traits\UserTrait;
    use Carbon\CarbonPeriod;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;


    class ExpenseController extends Controller
    {
        use UserTrait , DateTrait;

        public function index ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id           = $this -> userID( $request );
            $date_range_errors = $this -> validateDate( $request );
            if ( $date_range_errors ) return Response ::error( $date_range_errors );
            [ $start_date , $end_date ] = $this -> dateRange( $request );

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
                'expense_percentage' => $previous_month_total_expenses == 0 ? 0 : number_format( (($this_month_total_expenditure - $previous_month_total_expenses) / $previous_month_total_expenses) * 100 , 1 ) ,
                'income_percentage'  => $previous_month_total_sales == 0 ? 0 : number_format( (($this_month_total_sales - $previous_month_total_sales) / $previous_month_total_sales) * 100 , 1 ) ,
                'expenses'           => $expense_data
            ];
            return Response ::success( $data );
        }

        public function store ( StoreExpenseRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated        = $request -> validated();
            $expense_category = ExpenseCategory ::create( [
                'name'    => $validated[ 'name' ] ,
                'user_id' => $validated[ 'user_id' ] ,
            ] );
            if ( !$expense_category ) return Response ::error( $validator -> errors() -> first() );

            $validated[ 'date' ] = date( 'Y-m-d' , strtotime( $request -> date ) );
            $expense             = Expense ::create( [
                'amount'     => $validated[ 'amount' ] ,
                'user_id'    => $validated[ 'user_id' ] ,
                'date'       => $validated[ 'date' ] ,
                'expense_id' => $expense_category -> id ,
            ] );
            if ( !$expense ) return Response ::error( $validator -> errors() -> first() );
            return Response ::success( $expense , 201 );


        }

        public function expensesAndIncomes ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id           = $this -> userID( $request );
            $date_range_errors = $this -> validateDate( $request );
            if ( $date_range_errors ) return Response ::error( $date_range_errors );
            [ $start_date , $end_date ] = $this -> dateRange( $request );

            $difference_in_days  = $start_date -> diffInDays( $end_date );
            $monthly_expenses    = new Collection();
            $all_weekly_expenses = new Collection();
            if ( $difference_in_days <= 7 ) {
                $weekly_expenses = DB ::table( 'inv_expenses' )
                                      -> selectRaw( 'DAYNAME(date) AS day,DATE(date) AS cdate, SUM(amount) AS total' )
                                      -> where( 'inv_expenses.user_id' , $user_id )
                                      -> whereBetween( 'inv_expenses.date' , [ $start_date , $end_date ] )
                                      -> groupBy( 'day' , 'cdate' )
                                      -> orderBy( 'cdate' );
                $period          = CarbonPeriod ::create( $start_date , $end_date );
                $dates           = [];
                foreach ( $weekly_expenses -> get() as $weekly_expense ) {
                    $dates[] = collect( $weekly_expense ) [ 'cdate' ];
                }
                $all_weekly_expenses = $weekly_expenses -> get() -> slice( 0 );
                foreach ( $period as $date_period ) {
                    $date = $date_period -> toDate() -> format( 'Y-m-d' );
                    // Adding records to fill the gaps
                    if ( !in_array( $date , $dates ) ) {
                        $gap = [ 'day' => $date_period -> toDate() -> format( 'l' ) , 'cdate' => $date , 'total' => 0 ];
                        $all_weekly_expenses -> push( $gap );
                    }
                }
                $total_expenses = $weekly_expenses -> get() -> sum( 'total' );

            } else {
                $monthly_expenses = DB ::table( 'inv_expenses' )
                                       -> selectRaw( 'YEAR(date) AS year, SUM(amount) AS amount' )
                                       -> where( 'inv_expenses.user_id' , $user_id )
                                       -> whereBetween( 'inv_expenses.date' , [ $start_date , $end_date ] )
                                       -> groupBy( 'year' )
                                       -> orderBy( 'year' );
                $total_expenses   = $monthly_expenses -> get() -> sum( 'amount' );
            }

            $top_out_flow = DB ::table( 'inv_expenses' )
                               -> join( 'inv_expense_categories' , 'inv_expenses.expense_id' , '=' , 'inv_expense_categories.id' )
                               -> selectRaw( 'name,amount,inv_expenses.created_at AS date' )
                               -> where( 'inv_expenses.user_id' , $user_id )
                               -> whereBetween( 'inv_expenses.date' , [ $start_date , $end_date ] )
                               -> orderByRaw( 'date' )
                               -> take( 2 )
                               -> get();

            $sales = Sale :: whereHas( 'saleItems' )
                          -> where( 'user_id' , $user_id )
                          -> whereBetween( 'created_at' , [ $start_date , $end_date ] )
                          -> orderBy( 'created_at' , 'desc' )
                          -> take( 2 )
                          -> get();

            $sale_item = new Collection();
            foreach ( $sales as $sale ) {
                $item = [
                    'name'   => $sale -> saleItems() -> first() -> product() -> first() -> category() -> first() -> name ,
                    'date'   => $sale -> created_at ,
                    'amount' => $sale -> grand_total ,
                ];
                $sale_item -> push( $item );
            }

            $data = [
                'total_expenses' => $total_expenses ,
                'chart_data'     => ($difference_in_days <= 7) ? $all_weekly_expenses -> sortBy( 'cdate' ) -> values() : $monthly_expenses -> get() ,
                'top_out_flow'   => $top_out_flow ,
                'top_in_flow'    => $sale_item ,
            ];

            return Response ::success( $data );
        }

        public function topSection ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id           = $this -> userID( $request );
            $date_range_errors = $this -> validateDate( $request );
            if ( $date_range_errors ) return Response ::error( $date_range_errors );
            [ $start_date , $end_date ] = $this -> dateRange( $request );

            $total_expenses = DB ::table( 'inv_expenses' )
                                 -> join( 'inv_expense_categories' , 'inv_expenses.expense_id' , '=' , 'inv_expense_categories.id' )
                                 -> where( 'inv_expenses.user_id' , $user_id )
                                 -> whereBetween( 'date' , [ $start_date , $end_date ] )
                                 -> sum( 'amount' );

            $total_sales        = DB ::table( 'inv_sales' )
                                     -> where( 'user_id' , $user_id )
                                     -> whereBetween( 'created_at' , [ $start_date , $end_date ] )
                                     -> sum( 'grand_total' );
            $sales_percentage   = round( ($total_sales / ($total_expenses + $total_sales)) * 100 );
            $expense_percentage = round( ($total_expenses / ($total_expenses + $total_sales)) * 100 );

            $period = CarbonPeriod ::create( $start_date , $end_date );
            foreach ( $period as $date ) {
//                info( $date -> toDateString() );
            }
            return Response ::success( [
                'total_expenses'     => $total_expenses ,
                'sales_percentage'   => $sales_percentage ,
                'expense_percentage' => $expense_percentage ,
            ] );
        }

        public function bottomSection ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id           = $this -> userID( $request );
            $date_range_errors = $this -> validateDate( $request );
            if ( $date_range_errors ) return Response ::error( $date_range_errors );
            [ $start_date , $end_date ] = $this -> dateRange( $request );

            $expenses = DB ::table( 'inv_expenses' )
                           -> join( 'inv_expense_categories' , 'inv_expenses.expense_id' , '=' , 'inv_expense_categories.id' )
                           -> selectRaw( 'name,SUM(amount) AS total' )
                           -> groupBy( 'name' )
                           -> orderByRaw( 'SUM(amount) DESC' )
                           -> where( 'inv_expenses.user_id' , $user_id )
                           -> whereBetween( 'inv_expenses.date' , [ $start_date , $end_date ] );

            $latest_expenditure = DB ::table( 'inv_expenses' )
                                     -> join( 'inv_expense_categories' , 'inv_expenses.expense_id' , '=' , 'inv_expense_categories.id' )
                                     -> selectRaw( 'name,amount,date' )
                                     -> orderByRaw( 'inv_expenses.date DESC' )
                                     -> where( 'inv_expenses.user_id' , $user_id )
                                     -> whereBetween( 'inv_expenses.date' , [ $start_date , $end_date ] ) -> take( 1 ) -> get();

            $total_expenses = $expenses -> sum( 'amount' );

            return Response ::success( [
                'total_expenses'     => $total_expenses ,
                'expenses'           => $expenses -> take( 4 ) -> get() ,
                'latest_expenditure' => $latest_expenditure
            ] );
        }
    }

