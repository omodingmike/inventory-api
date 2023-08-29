<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\CustomValidator;
    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSaleRequest;
    use App\Models\inventory\CartItem;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class SaleController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param Request $request
         * @return array
         */
        public function index ( Request $request )
        {
            $validator = CustomValidator ::validateUserId( $request );
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                return Response ::error( $validator -> messages() -> first() );
            }
            $user_id              = $request -> user_id;
            $date_range_validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'bail|required|date' ,
                    'to'   => 'bail|required|date' ,
                ]
            );
            if ( $date_range_validator -> fails() ) return Response ::error( $date_range_validator -> errors() -> first() );
            $startDate = Carbon :: parse( $request -> query( 'from' ) ) -> startOfDay();
            $endDate   = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();
            $sales     = Sale :: ofUserID( $user_id )
                              -> without( 'saleItems' )
                              -> duration( $startDate -> copy() -> startOfDay() , $endDate -> copy() -> endOfDay() )
                              -> get();
            if ( $sales -> count() < 1 ) return Response ::error( 'No sales Found' );
            $total_products = 0;
            foreach ( $sales as $sale ) {
                $items = $sale -> saleItems;
                if ( count( $items ) > 0 ) {
                    foreach ( $items as $sale_item ) {
                        $total_products += $sale_item -> quantity;
                    }
                }
            }
            $data = [
                'products_sold' => $total_products ,
                'sales'         => $sales -> each( function ( $sale ) { $sale -> makeHidden( 'saleItems' ); } )
            ];
            return Response ::success( $data );
        }

        public function show ( Request $request )
        {
            $validator = CustomValidator ::validateUserId( $request );
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                return Response ::error( $validator -> messages() -> first() );
            }
            $user_id         = $request -> user_id;
            $validate_saleID = Validator ::make( $request -> all() , [ 'sale_id' => 'bail|required|string|exists:inv_sales,sale_id' ] );
            if ( $validate_saleID -> stopOnFirstFailure() -> fails() ) {
                return Response ::error( $validate_saleID -> messages() -> first() );
            }
            $sale_details = Sale ::where( [ 'user_id' => $user_id , 'sale_id' => $request -> sale_id ] )
                                 -> with( 'customer' )
                                 -> with( 'saleItems.product' ) -> first();
            if ( $sale_details ) return Response ::success( $sale_details );
            else return Response ::error( 'No details found' );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreSaleRequest $request
         * @return string[]
         */
        public function store ( StoreSaleRequest $request )
        {
            DB ::beginTransaction();
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated               = $request -> validated();
            $user_id                 = $validated [ 'user_id' ];
            $validated [ 'sale_id' ] = 'S' . time();
            $sale                    = Sale ::create( $validated );

            foreach ( $validated [ 'items' ] as $item ) {
                $item[ 'sale_id' ] = $sale -> id;
                $product           = Product ::ofUserID( $user_id )
                                             -> where( 'name' , $item [ 'name' ] )
                                             -> first();

                if ( !$product ) return Response ::error( 'No products found for this user' );
                $item[ 'product_id' ] = $product -> id;
                $product -> increment( 'sold' , $item [ 'quantity' ] );
                CartItem ::create( $item );
            }
            DB ::commit();
            return Response ::success( $sale );
        }
    }
