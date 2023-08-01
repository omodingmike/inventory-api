<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Models\inventory\User;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
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
            $user_id    = $request -> user_id;
            $user       = User ::find( $user_id );
            $end_date   = Carbon ::now();
            $start_date = Carbon ::now();
            //            $startDate = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
//            $endDate   = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();
            switch ( $request -> duration ) {
                case '1d':
                    $start_date = $end_date -> copy() -> subDay();
                    break;
                case '1w':
                    $start_date = $end_date -> copy() -> subWeek();
                    break;
                case '1m':
                    $start_date = $end_date -> copy() -> subMonth();
                    break;
                case '3m':
                    $start_date = $end_date -> copy() -> subQuarter();
                    break;
                case '6m':
                    $start_date = $end_date -> copy() -> subMonths( 6 );
                    break;
                case '1y':
                    $start_date = $end_date -> copy() -> subYear();
                    break;
            }

//            $end_date = Carbon ::now();

            $products_in = Product ::where( 'user_id' , $user_id )
                                   -> whereBetween( 'created_at' , [ $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() ] )
                                   -> sum( 'quantity' );

            $revenue_at_start_date = Sale ::where( 'user_id' , $user_id )
                                          -> whereBetween( 'created_at' , [ $start_date -> copy() -> startOfDay() , $start_date -> copy() -> endOfDay() ] )
                                          -> sum( 'grand_total' );
            $revenue_at_end_date   = Sale ::where( 'user_id' , $user_id )
                                          -> whereBetween( 'created_at' , [ $end_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() ] )
                                          -> sum( 'grand_total' );

            $percentage_change = $revenue_at_start_date == 0 ? 0 : number_format( ( ( $revenue_at_end_date - $revenue_at_start_date ) / $revenue_at_start_date ) * 100 , 1 );

            $products_out = 0;
            $revenues     = Sale ::where( 'user_id' , $user_id )
                                 -> whereBetween( 'created_at' , [ $start_date -> copy() -> startOfDay() , $end_date -> copy() -> endOfDay() ] )
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
                        'products_in'  => (int) $products_in ,
                        'products_out' => $products_out ,
                        'total_sales'  => count( $revenues ) ,
                        'percentage'   => $percentage_change ,
                        'total'        => $total_revenue ,
                        'revenues'     => $revenues
                    ]
                ];
            } else {
                return [
                    'status'  => 0 ,
                    'message' => 'User not found'
                ];
            }

        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store ( Request $request )
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return Response
         */
        public function show ( $id )
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         * @return Response
         */
        public function update ( Request $request , $id )
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return Response
         */
        public function destroy ( $id )
        {
            //
        }
    }
