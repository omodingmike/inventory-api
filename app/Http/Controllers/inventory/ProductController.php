<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Models\inventory\Product;
    use Exception;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
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
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Product ::with( 'supplier' , 'units' , 'productCategory' , 'productSubCategory' )
                                         -> where( 'user_id' , $request -> user_id )
                                         -> get()
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

        public function details ( Request $request )
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Product ::ofUserID( $request -> user_id )
                                         -> ofID( $request -> id )
                                         -> with( 'supplier' , 'units' , 'productCategory' , 'productSubCategory' )
                                         -> where( 'user_id' , $request -> user_id )
                                         -> first()
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

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return array
         */
        public function store ( Request $request )
        {
            try {
                DB ::beginTransaction();
                $store_data            = $request -> all();
                $store_data[ 'photo' ] = Uploads ::upload_image( $request , 'photo' );

                $store_data[ 'supplier' ] = DB ::table( 'inv_suppliers' )
                                               -> where( 'name' , $request -> supplier )
                                               -> first() -> id;

                $store_data[ 'productCategory' ] = DB ::table( 'inv_categories' )
                                                      -> where( 'name' , $request -> productCategory )
                                                      -> first() -> id;

                $store_data[ 'productSubCategory' ] = DB ::table( 'inv_sub_categories' )
                                                         -> where( 'name' , $request -> productSubCategory )
                                                         -> first() -> id;

                $store_data[ 'units' ]   = DB ::table( 'inv_units' )
                                              -> where( 'name' , $request -> units )
                                              -> first() -> id;
                $store_data[ 'balance' ] = $request -> quantity * $request -> retailPrice;

                Product ::create( $store_data );
                DB ::commit();
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'message' => 'Product created successfully' ,
                    ]
                ];
            }
            catch ( Exception $exception ) {
                DB ::rollBack();
                return [
                    'status'  => 0 ,
                    'message' => 'failed' ,
                    'data'    => [
                        'message' => $exception -> getMessage() ,
                        'file'    => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line'    => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
                ];
            }
        }

        public function filterProducts ( Request $request )
        {
            try {
                $user_id        = $request -> user_id;
                $start_date     = Carbon ::parse( $request -> query( 'from' ) ) -> copy() -> startOfDay();
                $end_date       = Carbon ::parse( $request -> query( 'to' ) ) -> copy() -> endOfDay();
                $products       = Product ::ofUserID( $user_id )
                                          -> duration( $start_date , $end_date )
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
                    'message' => $exception -> getMessage() ,
                    'data'    => [
                        'file' => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line' => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
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
                    'message' => $exception -> getMessage() ,
                    'data'    => [
                        'file' => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line' => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
                ];
            }
        }

    }
