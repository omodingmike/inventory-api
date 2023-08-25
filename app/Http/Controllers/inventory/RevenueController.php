<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;

    class RevenueController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return array|string[]
         */
        public function index ( Request $request )
        {
            try {
                $user_id    = $request -> user_id;
                $start_date = Carbon ::parse( $request -> query( 'from' ) );
                $end_date   = Carbon ::parse( $request -> query( 'to' ) );

                $highest_revenues     = new Collection();
                $difference_in_days   = $start_date -> diffInDays( $end_date );
                $difference_in_months = $start_date -> diffInMonths( $end_date );
                $difference_in_weeks  = $start_date -> diffInWeeks( $end_date );

                if ( $difference_in_days <= 1 ) {
                    $highest_revenues = Sale ::ofUserID( $user_id )
                                             -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                             -> selectRaw( 'HOUR(created_at) AS hour, SUM(grand_total) AS amount' )
                                             -> groupBy( 'hour' )
                                             -> get();

                } elseif ( $difference_in_weeks <= 1 ) {
                    $highest_revenues = Sale ::ofUserID( $user_id )
                                             -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                             -> selectRaw( 'DAYNAME(created_at) AS day,DATE(created_at) AS cdate, SUM(grand_total) AS amount' )
                                             -> groupBy( 'cdate' , 'day' )
                                             -> orderBy( 'cdate' )
                                             -> get();
                } elseif ( $difference_in_days > 27 && $difference_in_days < 32 ) {
                    $month_revenues = Sale ::ofUserID( $user_id )
                                           -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                           -> selectRaw( 'WEEK(created_at) AS week,  SUM(grand_total) AS amount' )
                                           -> groupBy( 'week' )
                                           -> get();

                    foreach ( $month_revenues as $index => $weekly_revenue ) {
                        $highest_revenues -> push( [ 'week' => $index + 1 , 'amount' => $weekly_revenue -> amount ] );
                    }
                } elseif ( $difference_in_months >= 3 ) {
//                    $start_date       = $end_date -> copy() -> subQuarter();
                    $highest_revenues = Sale ::ofUserID( $user_id )
                                             -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                             -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month ,MONTH(created_at) AS month_number, MAX(grand_total) AS amount' )
                                             -> groupBy( 'year' , 'month' , 'month_number' )
                                             -> orderBy( 'year' )
                                             -> orderBy( 'month_number' )
                                             -> get();


                }
                $products_in = Product ::ofUserID( $user_id )
                                       -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                       -> sum( 'quantity' );

                $revenue_at_start_date = Sale ::ofUserID( $user_id )
                                              -> duration( $start_date -> copy() -> startOfDay() , $start_date -> copy() -> endOfDay() )
                                              -> sum( 'grand_total' );

                $revenue_at_end_date = Sale ::ofUserID( $user_id )
                                            -> duration( $end_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                            -> sum( 'grand_total' );

                $percentage_change = $revenue_at_start_date == 0 ? 0 : number_format( ( ( $revenue_at_end_date - $revenue_at_start_date ) / $revenue_at_start_date ) * 100 , 1 );

                $products_out = 0;

                $revenues = Sale ::ofUserID( $user_id )
                                 -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                 -> get( [ 'grand_total' , 'created_at' ] );

                $total_revenue = 0;
                DB ::table( 'inv_sales' )
                   -> where( 'user_id' , $user_id )
                   -> whereBetween( 'created_at' , [ $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() ] )
                   -> chunkById( 100 , function ( Collection $sales ) use ( &$products_out , &$total_revenue ) {
                       foreach ( $sales as $sale ) {
                           $total_revenue += $sale -> grand_total;
                           $products_out  += DB ::table( 'inv_cart_items' )
                                                -> where( 'sale_id' , $sale -> id )
                                                -> sum( 'quantity' );
                       }
                   } );

                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'products_in'   => (int) $products_in ,
                        'products_out'  => $products_out ,
                        'total_sales'   => count( $revenues ) ,
                        'percentage'    => $percentage_change ,
                        'total_revenue' => $total_revenue ,
                        'chart_data'    => $highest_revenues
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
    }
