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
            $user_id           = $this -> userID( $request );
            $date_range_errors = $this -> validateDate( $request );
            if ( $date_range_errors ) return Response ::error( $date_range_errors );
            [ $start_date , $end_date ] = $this -> dateRange( $request );

            $highest_revenues     = new Collection();
            $difference_in_days   = $start_date -> diffInDays( $end_date );
            $difference_in_months = $start_date -> diffInMonths( $end_date );
            $difference_in_weeks  = $start_date -> diffInWeeks( $end_date );

            if ( $difference_in_days <= 1 ) {
                $highest_revenues = Sale ::ofUserID( $user_id )
                                         -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                         -> selectRaw( 'HOUR(created_at) AS hour, CAST(SUM(grand_total) AS UNSIGNED) AS `amount`' )
                                         -> groupBy( 'hour' )
                                         -> get();
//
                $months = [];
                foreach ( $highest_revenues as $expense ) {
                    $months[] = collect( $expense ) [ 'hour' ];
                }
                $highest_revenues = $highest_revenues -> slice( 0 );
                for ( $i = 1 ; $i <= 24 ; $i++ ) {
                    if ( !in_array( $i , $months ) ) {
                        $gap = [ 'hour' => $i , 'amount' => 0 ];
                        $highest_revenues -> push( $gap );
                    }
                }
                $highest_revenues = collect( $highest_revenues ) -> sortBy( 'hour' ) -> values();
            } elseif ( $difference_in_weeks <= 1 ) {
                $highest_revenues = Sale ::ofUserID( $user_id )
                                         -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                         -> selectRaw( 'DAYNAME(created_at) AS day,DATE(created_at) AS cdate, CAST(SUM(grand_total) AS UNSIGNED) AS amount' )
                                         -> groupBy( 'cdate' , 'day' )
                                         -> orderBy( 'cdate' )
                                         -> get();
                $period           = CarbonPeriod ::create( $start_date , $end_date );
                $dates            = [];
                foreach ( $highest_revenues as $highest_revenue ) {
                    $dates[] = collect( $highest_revenue ) [ 'cdate' ];
                }
                $highest_revenues = $highest_revenues -> slice( 0 );
                foreach ( $period as $date_period ) {
                    $date = $date_period -> toDate() -> format( 'Y-m-d' );
                    // Adding records to fill the gaps
                    if ( !in_array( $date , $dates ) ) {
                        $gap = [ 'day' => $date_period -> toDate() -> format( 'l' ) , 'cdate' => $date , 'amount' => 0 ];
                        $highest_revenues -> push( $gap );
                    }
                }

            } elseif ( $difference_in_days > 27 && $difference_in_days < 32 ) {
                $month_revenues = Sale ::ofUserID( $user_id )
                                       -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                       -> selectRaw( 'WEEK(created_at) AS week, CAST(SUM(grand_total) AS UNSIGNED) AS amount' )
                                       -> groupBy( 'week' )
                                       -> get();

                foreach ( $month_revenues as $index => $weekly_revenue ) {
                    $highest_revenues -> push( [ 'week' => $index + 1 , 'amount' => $weekly_revenue -> amount ] );
                }
                $months = [];
                foreach ( $highest_revenues as $revenue ) {
                    $months[] = collect( $revenue ) [ 'week' ];
                }
                $highest_revenues = $highest_revenues -> slice( 0 );
                for ( $i = 1 ; $i <= $start_date -> diffInWeeks( $end_date ) ; $i++ ) {
                    if ( !in_array( $i , $months ) ) {
                        $gap = [ 'week' => $i , 'amount' => 0 ];
                        $highest_revenues -> push( $gap );
                    }
                }
                $highest_revenues = collect( $highest_revenues ) -> sortBy( 'week' ) -> values();

            } elseif ( $difference_in_months >= 2 ) {
                $highest_revenues = Sale ::ofUserID( $user_id )
                                         -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                                         -> selectRaw( 'YEAR(created_at) AS year, MONTHNAME(created_at) AS month' )
                                         -> selectRaw( 'MONTH(created_at) AS month_number' )
                                         -> selectRaw( 'CAST(MAX(grand_total) AS UNSIGNED) AS  amount' )
                                         -> groupBy( 'year' , 'month' , 'month_number' )
                                         -> orderBy( 'year' )
                                         -> orderBy( 'month_number' )
                                         -> get();

                $months = [];
                foreach ( $highest_revenues as $revenue ) {
//                    $months[] = collect( $revenue ) [ 'month_number' ];
                    $months[] = collect( $revenue ) [ 'month' ];
                }

                $highest_revenues  = collect( $highest_revenues ) -> sortBy( 'week' ) -> values();
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
                    if ( !in_array( $month , $months ) ) {
                        $gap = [ 'year' => (int) $year , 'month_number' => $i + 1 , 'month' => $month , 'amount' => 0 ];
                        $highest_revenues -> push( $gap );
                    }
                }
                $highest_revenues = collect( $highest_revenues ) -> sortBy( function ( $item ) {
                    return (int) $item[ 'month_number' ];
                } ) -> values();
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

            $percentage_change = $revenue_at_start_date == 0 ? 0 : round( (($revenue_at_end_date - $revenue_at_start_date) / $revenue_at_start_date) *
                100 , 1 );

            $products_out = 0;

            $revenues = Sale ::ofUserID( $user_id )
                             -> duration( $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() )
                             -> get( [ 'grand_total' , 'created_at' ] );

            if ( $revenues -> count() < 1 ) return Response ::error( 'No Revenues found' );
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

            $data = [
                'products_in'   => (int) $products_in ,
                'products_out'  => $products_out ,
                'total_sales'   => count( $revenues ) ,
                'percentage'    => $percentage_change ,
                'total_revenue' => $total_revenue ,
                'chart_data'    => $highest_revenues
            ];
            return Response ::success( $data );
        }
    }
