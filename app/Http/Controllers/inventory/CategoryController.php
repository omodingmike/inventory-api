<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Category;
    use Exception;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Intervention\Image\Facades\Image;
    use LaravelIdea\Helper\App\Models\inventory\_IH_Category_C;


    class CategoryController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Category[]|Collection|_IH_Category_C
         */
        public function index ()
        {
            try {
                return Category ::with( 'products.supplier' , 'products.units' ) -> get();
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 'failed' ,
                    'message' => $exception -> getMessage()
                ];
            }
        }

        public function getCategoryProducts ()
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => Category ::with( 'products.supplier' , 'products.units' ) -> get()

                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 'failed' ,
                    'message' => $exception -> getMessage()
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
                Category ::create( $validated );
                DB ::commit();
                return [
                    'status'  => 'ok' ,
                    'message' => 'success'
                ];
            }
            catch ( QueryException $exception ) {
                DB ::rollBack();
                return [
                    'status'  => 'failed' ,
                    'message' => $exception -> getMessage()
                ];
            }
        }
    }
