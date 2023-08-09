<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Models\inventory\Product;
    use Exception;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;

    class ProductController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Product[]
         */
        public function index ( Request $request )
        {
            $products = Product ::with( 'supplier' , 'units' , 'productCategory' , 'productSubCategory' )
                                -> where( 'user_id' , $request -> user_id )
                                -> get();
            if ( $products ) {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $products
                ];
            } else {
                return [
                    'status'  => 0 ,
                    'message' => 'No products found for this user' ,
                    'data'    => []
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

            $store_data            = $request -> all();
            $store_data[ 'photo' ] = Uploads ::upload_image( $request );

            $store_data[ 'supplier' ] = DB ::table( 'inv_suppliers' )
                                           -> where( 'name' , $request -> supplier )
                                           -> first() -> id;

            $store_data[ 'productCategory' ] = DB ::table( 'inv_categories' )
                                                  -> where( 'name' , $request -> productCategory )
                                                  -> first() -> id;

            $store_data[ 'productSubCategory' ] = DB ::table( 'inv_sub_categories' )
                                                     -> where( 'name' , $request -> productSubCategory )
                                                     -> first() -> id;

            $store_data[ 'units' ] = DB ::table( 'inv_units' )
                                        -> where( 'name' , $request -> units )
                                        -> first() -> id;

            return Product ::create( $store_data );
        }


        public function filterProducts ( Request $request )
        {
            try {
                $user_id        = $request -> user_id;
                $startDate      = Carbon ::parse( $request -> query( 'from' ) ) -> copy() -> startOfDay();
                $endDate        = Carbon ::parse( $request -> query( 'to' ) ) -> copy() -> endOfDay();
                $products       = Product ::ofUserID( $user_id )
                                          -> duration( $startDate , $endDate )
                                          -> with( 'supplier' , 'units' , 'productCategory' , 'productSubCategory' )
                                          -> where( 'productCategory' , $request -> query( 'productCategory' ) )
                                          -> get();
                $total_quantity = 0;
                $total_balance  = 0;
                foreach ( $products as $product ) {
                    $total_quantity += $product -> quantity;
                    $total_balance  += $product -> balance;
                }
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'total_quantity' => $total_quantity ,
                        'total_balance'  => $total_balance ,
                        'products'       => $products
                    ]
                ];

            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => 'failed' ,
                    'data'    => []
                ];
            }

        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function update ( Request $request )
        {
            try {
                DB ::beginTransaction();
                $product                             = Product ::find( $request -> id );
                $update_data                         = $request -> except( 'id' );
                $update_data[ 'supplier' ]           = DB ::table( 'inv_suppliers' )
                                                          -> where( 'name' , $request -> supplier )
                                                          -> first() -> id;
                $update_data[ 'productCategory' ]    = DB ::table( 'inv_categories' )
                                                          -> where( 'name' , $request -> productCategory )
                                                          -> first() -> id;
                $update_data[ 'productSubCategory' ] = DB ::table( 'inv_sub_categories' )
                                                          -> where( 'name' , $request -> productSubCategory )
                                                          -> first() -> id;
                $update_data[ 'units' ]              = DB ::table( 'inv_units' )
                                                          -> where( 'name' , $request -> units )
                                                          -> first() -> id;
                $product -> update( $update_data );
                DB ::commit();
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => []
                ];
            }
            catch ( QueryException $exception ) {
                DB ::rollBack();
                return [
                    'status'  => 0 ,
                    'message' => 'Update failed' ,
                    'data'    => []
                ];
            }
        }
    }
