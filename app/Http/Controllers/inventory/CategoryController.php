<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Category;
    use Exception;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Intervention\Image\Facades\Image;


    class CategoryController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return array
         */
        public function index ( Request $request )
        {
            try {
                $user_id      = $request -> user_id;
                $categories   = Category ::ofUserID( $user_id )
                                         -> has( 'products' )
                                         -> with( [ 'products' => function ( $query ) use ( $user_id ) {
                                             $query -> where( 'user_id' , $user_id );
                                         } , 'products.supplier' , 'products.units' ] )
//                                         -> groupBy( 'name' )
//                                         -> groupBy()
//                                         -> select( [ 'name' , 'photo' , 'description' ] )
                                         -> get();
                $data         = [];
                $out_of_stock = 0;
                foreach ( $categories as $category ) {
                    $test                = [
                        'id'          => $category -> id ,
                        'name'        => $category -> name ,
                        'description' => $category -> description ,
                        'photo'       => $category -> photo ,
                    ];
                    $category_collection = collect( $test );
                    $stock_value         = 0;
                    $stock_quantity      = 0;
                    $sold_quantity       = 0;
                    foreach ( $category -> products as $product ) {
                        $stock_value    += $product -> balance;
                        $stock_quantity += $product -> quantity;
                        $sold_quantity  += $product -> sold;
                        if ( $product -> quantity < 1 )
                            $out_of_stock++;
                        $category_collection -> put( 'stock_value' , $stock_value );
                    }
                    $percentage_stock = (int) ( ( ( $stock_quantity - $sold_quantity ) / $stock_quantity ) * 100 );
                    if ( $percentage_stock <= 30 )
                        $category_collection -> put( 'status' , 'Low' );
                    else if ( $percentage_stock <= 50 )
                        $category_collection -> put( 'status' , 'Medium' );
                    else  $category_collection -> put( 'status' , 'Good' );

                    $data[] = $category_collection;
                }
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [
                        'out_of_stock' => $out_of_stock ,
                        'categories'   => $data ,
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

        public function categoryProducts ()
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Category ::with( 'products.supplier' , 'products.units' , 'products.productSubCategory' )
                                          -> get() ];
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

        public function getCategoryProducts ( Request $request )
        {
            $user_id = $request -> user_id;
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Category ::has( 'products' )
                                          -> with( [ 'products' => function ( $query ) use ( $user_id ) {
                                              $query -> where( 'user_id' , $user_id );
                                          } , 'products.supplier' , 'products.units' ] )
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
                $validated      = $request -> validate( [ 'name' => 'required|string' , 'photo' => 'required|image' ] );
                $uploaded_image = $request -> file( 'photo' );
                $filename       = 'public/images/' . time() . '.' . $uploaded_image -> getClientOriginalExtension();
                $image          = Image ::make( $uploaded_image );
                $image -> resize( 100 , 100 );
                Storage ::put( $filename , $image -> encode() );
                $validated[ 'photo' ] = url( '/' ) . Storage ::url( $filename );
                $category             = Category ::create( $validated );
                DB ::commit();
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $category
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
