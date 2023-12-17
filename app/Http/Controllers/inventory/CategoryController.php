<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreCategoryRequest;
    use App\Models\inventory\Category;
    use App\Traits\AWSTrait;
    use App\Traits\UserTrait;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class CategoryController extends Controller
    {
        use UserTrait , AWSTrait;

        public function index ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id    = $this -> userID( $request );
            $categories = DB ::table( 'inv_categories' )
                             -> join( 'inv_products' , 'inv_categories.id' , '=' , 'inv_products.category' )
                             -> selectRaw( 'inv_categories.id, inv_categories.name, inv_categories.photo, inv_categories.description' )
                             -> selectRaw( 'CAST(SUM(COALESCE(inv_products.quantity*inv_products.retail_price, 0)) AS UNSIGNED) AS stock_value' )
                             -> selectRaw( 'CASE 
                                    WHEN (SUM(COALESCE(inv_products.quantity, 0) - COALESCE(inv_products.sold, 0)) / SUM(COALESCE(inv_products.quantity, 1))) * 100 <= 30 THEN "low"
                                    WHEN (SUM(COALESCE(inv_products.quantity, 0) - COALESCE(inv_products.sold, 0)) / SUM(COALESCE(inv_products.quantity, 1))) * 100 <= 50 THEN "medium"
                                    ELSE "good" END AS status' )
                             -> where( 'inv_products.user_id' , '=' , $user_id )
                             -> groupBy( 'inv_categories.id' , 'inv_categories.name' , 'inv_categories.photo' , 'description' )
                             -> get();
            foreach ( $categories as $category ) {
                if ( $category -> photo ) {
                    $category -> photo = $this -> getUri( $category -> photo );
                }
            }

            $out_of_stock = DB ::table( 'inv_products' )
                               -> where( 'user_id' , '=' , $user_id )
                               -> where( 'quantity' , '<' , 1 )
                               -> count();

            $products = DB ::table( 'inv_products' )
                           -> where( 'user_id' , $user_id )
                           -> limit( 10 )
                           -> get();

            return Response ::success( [
                'out_of_stock' => $out_of_stock ,
                'categories'   => $categories ,
                'products'     => $products ,
            ] );
        }

        public function store ( StoreCategoryRequest $request )
        {
            try {
                DB ::beginTransaction();
                $validator = $request -> validator;
                if ( $validator -> fails() ) {
                    return Response ::error( $validator -> errors() -> first() );
                }
                $validated            = $request -> validated();
                $validated[ 'photo' ] = Uploads ::uploadFile( $request , 'photo' );
                $category             = Category ::create( $validated );
                DB ::commit();
                if ( $category ) {
                    return Response ::success( $category , 201 );
                } else {
                    return Response ::error( 'Category could not be created' );
                }
            }
            catch ( QueryException $exception ) {
                DB ::rollBack();
                return Response ::error( $exception -> getMessage() );
            }
        }

        public function all ( Request $request )
        {
            $categories = DB ::table( 'inv_categories' )
                             -> where( 'user_id' , $request -> input( 'user_id' ) )
                             -> get();
            if ( $categories ) return Response ::success( $categories );
            return Response ::error( "No Categories" );
        }
    }
