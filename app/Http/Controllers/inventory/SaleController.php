<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\CartItem;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Models\inventory\User;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\DB;

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
            $user = User ::find( $request -> user_id );
            if ( $user ) {
                $total_products = 0;
                $all_sales      = $user -> sales;
                foreach ( $all_sales as $sale ) {
                    $total_products += CartItem ::where( 'sale_id' , $sale -> id ) -> sum( 'quantity' );
                }
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'products_sold' => $total_products ,
                        'sales'         => $all_sales
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

        public function show ( Request $request )
        {
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    =>
                    Sale ::where(
                        [
                            'user_id' => $request -> user_id ,
                            'sale_id' => $request -> sale_id
                        ] )
                         -> with( 'customer' )
                         -> with( 'saleItems.product' ) -> first()
            ];


        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store ( Request $request )
        {
            try {
                DB ::beginTransaction();
                $sale = Sale ::create( [
                    'sale_id'     => 'S' . time() ,
                    'user_id'     => Arr ::get( $request , 'user_id' ) ,
                    'grand_total' => Arr ::get( $request , 'grandTotal' ) ,
                    'contact_id'  => Arr ::get( $request , 'customerID' ) ,
                    'mode'        => Arr ::get( $request , 'payment_mode' )
                ] );

                foreach ( Arr ::get( $request , 'items' ) as $item ) {
                    $item[ 'sale_id' ] = $sale -> id;
                    CartItem ::create( $item );
                    Product ::find( $item [ 'productID' ] ) -> decrement( 'quantity' , $item [ 'quantity' ] );
                }
                DB ::commit();
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $sale
                ];

            }
            catch ( QueryException $exception ) {
                DB ::rollBack();
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => []
                ];
            }
        }
    }
