<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Models\User;
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
            $user_id = $request -> user_id;
            $user    = User ::find( $user_id );
//            $start_date = Carbon ::now();
//            $end_date   = Carbon ::now();

            $start_date = Carbon ::parse( $request -> query( 'from' ) );
            $end_date   = Carbon ::parse( $request -> query( 'to' ) );
            info( $start_date );
            info( $end_date );

//            $difference_in_days = $start_date -> diffInDays( $end_date );

            $highest_revenues = new Collection();
            if ( $start_date -> diffInDays( $end_date ) == 1 ) {
                $start_date       = $end_date -> copy() -> subDay();
                $highest_revenues = Sale ::ofUserID( $user_id )
                                         -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                             -> selectRaw( 'DATE(created_at) AS date, HOUR(created_at) AS hour, MAX(grand_total) AS amount' )
                                         -> selectRaw( 'HOUR(created_at) AS hour, SUM(grand_total) AS amount' )
                                         -> groupBy( 'hour' )
                                         -> get();
            } elseif ( $start_date -> diffInWeeks( $end_date ) == 7 ) {
                $start_date       = $end_date -> copy() -> subWeek();
                $highest_revenues = Sale ::ofUserID( $user_id )
                                         -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                         -> selectRaw( 'DAYNAME(created_at) AS day, SUM(grand_total) AS amount' )
                                         -> groupBy( 'day' )
                                         -> get();
            } elseif ( $start_date -> diffInMonths( $end_date ) == 1 ) {
                $start_date     = $end_date -> copy() -> subMonth();
                $month_revenues = Sale ::ofUserID( $user_id )
                                       -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                       -> selectRaw( 'WEEK(created_at) AS week, SUM(grand_total) AS amount' )
                                       -> groupBy( 'week' )
                                       -> get();
                foreach ( $month_revenues as $index => $weekly_revenue ) {
                    $highest_revenues -> push( [ 'week' => $index + 1 , 'amount' => $weekly_revenue -> amount ] );
                }
            } elseif ( $start_date -> diffInMonths( $end_date ) > 1 ) {
                $start_date       = $end_date -> copy() -> subQuarter();
                $highest_revenues = Sale ::ofUserID( $user_id )
                                         -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                         -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month, MAX(grand_total) AS amount' )
                                         -> groupBy( 'year' , 'month' )
//                                             -> orderBy( 'year' , 'desc' )
//                                             -> orderBy( 'month' , 'desc' )
                                         -> get();
            }


//            switch ( $request -> duration ) {
//                case '1d':
//                    $start_date       = $end_date -> copy() -> subDay();
//                    $highest_revenues = Sale :: duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
////                                             -> selectRaw( 'DATE(created_at) AS date, HOUR(created_at) AS hour, MAX(grand_total) AS amount' )
//                                             -> selectRaw( 'HOUR(created_at) AS hour, MAX(grand_total) AS amount' )
//                                             -> groupBy( 'hour' )
//                                             -> get();
//                    break;
//                case '1w':
//                    $start_date       = $end_date -> copy() -> subWeek();
//                    $highest_revenues = Sale :: duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                             -> selectRaw( 'DAYNAME(created_at) AS day, MAX(grand_total) AS amount' )
//                                             -> groupBy( 'day' )
//                                             -> get();
//                    break;
//                case '1m':
//                    $start_date     = $end_date -> copy() -> subMonth();
//                    $month_revenues = Sale :: duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                           -> selectRaw( 'WEEK(created_at) AS week, MAX(grand_total) AS amount' )
//                                           -> groupBy( 'week' )
//                                           -> get();
//                    foreach ( $month_revenues as $index => $weekly_revenue ) {
//                        $highest_revenues -> push( [ 'week' => $index + 1 , 'amount' => $weekly_revenue -> amount ] );
//                    }
//                    break;
//                case '3m':
//                    $start_date       = $end_date -> copy() -> subQuarter();
//                    $highest_revenues = Sale :: duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                             -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month, MAX(grand_total) AS amount' )
//                                             -> groupBy( 'year' , 'month' )
////                                             -> orderBy( 'year' , 'desc' )
////                                             -> orderBy( 'month' , 'desc' )
//                                             -> get();
//
//                    break;
//                case '6m':
//                    $start_date       = $end_date -> copy() -> subMonths( 6 );
//                    $highest_revenues = Sale :: duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                             -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month, MAX(grand_total) AS amount' )
//                                             -> groupBy( 'year' , 'month' )
//                                             -> get();
////                    foreach ( $six_month_revenue as $index => $month_revenue ) {
////                        $highest_revenues -> push( [ 'month' => $index + 1 , 'amount' => $month_revenue -> amount ] );
////                    }
//                    break;
//                case '1y':
//                    $start_date       = $end_date -> copy() -> subYear();
//                    $highest_revenues = Sale :: duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
//                                             -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month, MAX(grand_total) AS amount' )
//                                             -> groupBy( 'year' , 'month' )
//                                             -> get();
////                    foreach ( $yearly_revenue as $index => $month_revenue ) {
////                        $highest_revenues -> push( [ 'month' => $index + 1 , 'amount' => $month_revenue -> amount ] );
////                    }
//                    break;
//            }

            $products_in = Product ::ofUserID( $user_id )
                                   -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                   -> sum( 'quantity' );

            $revenue_at_start_date = Sale ::ofUserID( $user_id )
                                          -> duration( $start_date -> copy() -> startOfDay() , $start_date -> copy() -> endOfDay() )
                                          -> sum( 'grand_total' );
            $revenue_at_end_date   = Sale ::ofUserID( $user_id )
                                          -> duration( $end_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                          -> sum( 'grand_total' );
            info( 'start revenue ->' . $revenue_at_start_date );
            info( 'end revenue ->' . $revenue_at_end_date );

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

            if ( $user ) {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'products_in'   => (int) $products_in ,
                        'products_out'  => $products_out ,
                        'total_sales'   => count( $revenues ) ,
                        'percentage'    => $percentage_change ,
                        'total_revenue' => $total_revenue ,
//                        'revenues'     => $revenues ,
                        'chart_data'    => $highest_revenues
                    ]
                ];
            } else {
                return [
                    'status'  => 0 ,
                    'message' => 'User not found' ,
                    'data'    => []
                ];
            }

        }
    }
