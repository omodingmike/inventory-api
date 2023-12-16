<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Traits\DateTrait;
    use App\Traits\UserTrait;
    use Carbon\CarbonPeriod;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;

    class RevenueController extends Controller
    {
        use UserTrait , DateTrait;

        public function index ( Request $request )
        {

            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id = $this -> userID( $request );

            $date_range_errors = $this -> validateDate( $request );
            if ( $date_range_errors ) return Response ::error( $date_range_errors );
            [ $start_date , $end_date ] = $this -> dateRange( $request );

            $sales                = new Collection();
            $difference_in_days   = $start_date -> diffInDays( $end_date );
            $difference_in_months = $start_date -> diffInMonths( $end_date );
            $difference_in_weeks  = $start_date -> diffInWeeks( $end_date );

            if ( $difference_in_days <= 1 ) {
                $sales = Sale ::ofUserID( $user_id )
                              -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                              -> selectRaw( 'HOUR(created_at) AS hour, CAST(SUM(grand_total) AS UNSIGNED) AS `amount`' )
                              -> groupBy( 'hour' )
                              -> get();

//                $total_revenue_at_start_date = $sales->sum('grand_total');

                $hours = [];
                foreach ( $sales as $expense ) {
                    $hours[] = collect( $expense ) [ 'hour' ];
                }
                $sales = $sales -> slice( 0 );
                for ( $hour = 0 ; $hour < 24 ; $hour++ ) {
                    if ( !in_array( $hour , $hours ) ) {
                        $sales -> push( [ 'hour' => $hour , 'amount' => 0 ] );
                    }
                }
                $sales = collect( $sales ) -> sortBy( 'hour' ) -> values();
            } elseif ( $difference_in_weeks <= 1 ) {
                $sales = Sale ::ofUserID( $user_id )
                              -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                              -> selectRaw( 'DAYNAME(created_at) AS day,DATE(created_at) AS cdate, CAST(SUM(grand_total) AS UNSIGNED) AS amount' )
                              -> groupBy( 'cdate' , 'day' )
                              -> orderBy( 'cdate' )
                              -> get();


                $period = CarbonPeriod ::create( $start_date , $end_date );
                $dates  = [];
                foreach ( $sales as $highest_revenue ) {
                    $dates[] = collect( $highest_revenue ) [ 'cdate' ];
                }
                $sales = $sales -> slice( 0 );
                foreach ( $period as $date_period ) {
                    $date = $date_period -> toDate() -> format( 'Y-m-d' );
                    // Adding records to fill the gaps
                    if ( !in_array( $date , $dates ) ) {
                        $sales -> push( [ 'day' => $date_period -> toDate() -> format( 'l' ) , 'cdate' => $date , 'amount' => 0 ] );
                    }
                }

            } elseif ( $difference_in_days > 27 && $difference_in_days < 32 ) {
                $month_revenues = Sale ::ofUserID( $user_id )
                                       -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                       -> selectRaw( 'WEEK(created_at) AS week, CAST(SUM(grand_total) AS UNSIGNED) AS amount' )
                                       -> groupBy( 'week' )
                                       -> get();

                foreach ( $month_revenues as $index => $weekly_revenue ) {
                    $sales -> push( [ 'week' => $index + 1 , 'amount' => $weekly_revenue -> amount ] );
                }
                $hours = [];
                foreach ( $sales as $revenue ) {
                    $hours[] = collect( $revenue ) [ 'week' ];
                }
                $sales = $sales -> slice( 0 );
                for ( $i = 1 ; $i <= $start_date -> diffInWeeks( $end_date ) ; $i++ ) {
                    if ( !in_array( $i , $hours ) ) {
                        $gap = [ 'week' => $i , 'amount' => 0 ];
                        $sales -> push( $gap );
                    }
                }
                $sales = collect( $sales ) -> sortBy( 'week' ) -> values();

            } elseif ( $difference_in_months >= 2 ) {
                $sales = Sale ::ofUserID( $user_id )
                              -> duration( $start_date , $end_date )
                              -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month' )
                              -> selectRaw( 'MONTH(created_at) AS month_number' )
                              -> selectRaw( 'CAST(SUM(grand_total) AS UNSIGNED) AS  amount' )
                              -> groupBy( 'year' , 'month' , 'month_number' )
                              -> orderBy( 'year' )
                              -> orderBy( 'month_number' )
                              -> get();

                $hours = [];
                foreach ( $sales as $revenue ) {
//                    $months[] = collect( $revenue ) [ 'month_number' ];
                    $hours[] = collect( $revenue ) [ 'month' ];
                }

                $sales             = collect( $sales ) -> sortBy( 'week' ) -> values();
                $period            = CarbonPeriod ::create( $start_date , $end_date );
                $monthsInDateRange = [];
                foreach ( $period as $date ) {
                    if ( !in_array( $date -> format( 'F Y' ) , $monthsInDateRange ) ) {
                        $monthsInDateRange[] = $date -> format( 'F Y' );
                    }
                }

                for ( $i = 0 ; $i < count( $monthsInDateRange ) ; $i++ ) {
                    $month = substr( $monthsInDateRange[ $i ] , 0 , strpos( $monthsInDateRange[ $i ] , ' ' ) );
                    $year  = substr( $monthsInDateRange[ $i ] , strpos( $monthsInDateRange[ $i ] , ' ' ) + 1 );
                    if ( !in_array( $month , $hours ) ) {
                        $gap = [ 'year' => (int) $year , 'month_number' => $i + 1 , 'month' => $month , 'amount' => 0 ];
                        $sales -> push( $gap );
                    }
                }
                $sales = collect( $sales ) -> sortBy( function ( $item ) {
                    return (int) $item[ 'month_number' ];
                } ) -> values();
            }
            $products_in = Product ::ofUserID( $user_id )
                                   -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                   -> sum( 'quantity' );

            $total_revenue_at_start_date = Sale ::ofUserID( $user_id )
                                                -> duration( $start_date -> copy() -> startOfDay() , $start_date -> copy() -> endOfDay() )
                                                -> sum( 'grand_total' );

            $revenue_at_end_date = Sale ::ofUserID( $user_id )
                                        -> duration( $end_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                        -> sum( 'grand_total' );

            $percentage_change_in_revenue = 0;

            if ( $total_revenue_at_start_date != 0 ) {
                $percentage_change_in_revenue = round(
                    (($revenue_at_end_date - $total_revenue_at_start_date) / $total_revenue_at_start_date) * 100 , 1 );
            }

//            $products_out = 0;

            $revenues = Sale ::ofUserID( $user_id )
                             -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                             -> get( [ 'grand_total' , 'created_at' ] );


            $products_out = (int) DB ::table( 'inv_sales' )
                                     -> select( 'inv_cart_items.quantity' )
                                     -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
                                     -> where( 'inv_sales.user_id' , '=' , $user_id )
                                     -> whereBetween( 'inv_sales.created_at' , [ $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() ] )
                                     -> sum( 'inv_cart_items.quantity' );

            $data = [
                'products_in'   => (int) $products_in ,
                'products_out'  => $products_out ,
                'total_sales'   => count( $revenues ) ,
                'percentage'    => $percentage_change_in_revenue ,
                'total_revenue' => $sales -> sum( 'amount' ) ,
                'chart_data'    => $sales
            ];
            return Response ::success( $data );
        }
    }
