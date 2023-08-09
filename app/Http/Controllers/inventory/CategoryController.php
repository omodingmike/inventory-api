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
        public function index ()
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Category ::with( 'products.supplier' , 'products.units' , 'products.productSubCategory' ) -> get() ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => []
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
//                    'data'    => Category ::has( 'products' ) -> with( 'products.supplier' , 'products.units' ) -> get()
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
                    'data'    => []
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
                    'data'    => []
                ];
            }
        }
    }
