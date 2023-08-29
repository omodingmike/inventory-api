<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreCategoryRequest;
    use App\Models\inventory\Category;
    use App\Models\User;
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
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id    = $request -> user_id;
            $categories = Category ::ofUserID( $user_id )
                                   -> has( 'products' )
                                   -> with( [ 'products' => function ( $query ) use ( $user_id ) {
                                       $query -> where( 'user_id' , $user_id );
                                   } , 'products.supplier' , 'products.units' ] )
                                   -> get();
            if ( $categories -> count() < 1 ) {
                return Response ::success( 'No Categories found' );
            }
            $categories_data = [];
            $out_of_stock    = 0;
            foreach ( $categories as $category ) {
                $category_item       = [
                    'id'          => $category -> id ,
                    'name'        => $category -> name ,
                    'description' => $category -> description ,
                    'photo'       => $category -> photo ,
                ];
                $category_collection = collect( $category_item );
                $stock_value         = 0;
                $stock_quantity      = 0;
                $sold_quantity       = 0;
                foreach ( $category -> products as $product ) {
                    $stock_value    += $product -> balance;
                    $stock_quantity += $product -> quantity;
                    $sold_quantity  += $product -> sold;
                    if ( $product -> quantity < 1 ) $out_of_stock++;
                    $category_collection -> put( 'stock_value' , $stock_value );
                }
                $percentage_stock = (int) ( ( ( $stock_quantity - $sold_quantity ) / $stock_quantity ) * 100 );
                if ( $percentage_stock <= 30 ) $category_collection -> put( 'status' , 'Low' );
                else if ( $percentage_stock <= 50 ) $category_collection -> put( 'status' , 'Medium' );
                else  $category_collection -> put( 'status' , 'Good' );
                $categories_data[] = $category_collection;
            }
            return Response ::success( [
                'out_of_stock' => $out_of_stock ,
                'categories'   => $categories_data ,
            ] );
        }

        public function categoryProducts ()
        {
            $category_with_products = Category ::with( 'products.supplier' , 'products.units' , 'products.sub_category' )
                                               -> get();
            if ( $category_with_products -> count() < 1 ) {
                return Response ::success( 'No products found for categories' );
            } else {
                return Response ::success( $category_with_products );
            }
        }

        public function getCategoryProducts ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id           = $request -> user_id;
            $category_products = Category ::has( 'products' )
                                          -> with( [ 'products' => function ( $query ) use ( $user_id ) {
                                              $query -> where( 'user_id' , $user_id );
                                          } , 'products.supplier' , 'products.units' ] )
                                          -> get();
            if ( $category_products < 1 ) {
                return Response ::success( 'No Category products found' );
            } else {
                return Response ::success( $category_products );
            }
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreCategoryRequest $request
         * @return string[]
         */
        public function store ( StoreCategoryRequest $request )
        {
            try {
                DB ::beginTransaction();
                $validator = $request -> validator;
                if ( $validator -> fails() ) {
                    return Response ::error( $validator -> errors() -> first() );
                }
                $validated      = $request -> validated();
                $uploaded_image = $request -> file( 'photo' );
                $filename       = 'public/images/' . time() . '.' . $uploaded_image -> getClientOriginalExtension();
                $image          = Image ::make( $uploaded_image );
                Storage ::put( $filename , $image -> encode() );
                $validated[ 'photo' ] = url( '/' ) . Storage ::url( $filename );
                $category             = Category ::create( $validated );
                DB ::commit();
                if ( $category ) {
                    return Response ::success( $category );
                } else {
                    return Response ::error( 'Category could not be created' );
                }
            }
            catch ( QueryException $exception ) {
                DB ::rollBack();
                return Response ::error( $exception -> getMessage() );
            }
        }
    }
