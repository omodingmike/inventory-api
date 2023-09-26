<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreCategoryRequest;
    use App\Models\inventory\Category;
    use App\Traits\UserTrait;
    use Illuminate\Database\QueryException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Intervention\Image\Facades\Image;

    class CategoryController extends Controller
    {
        use UserTrait;

        public function index ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id      = $this -> userID( $request );
            $categories   = DB ::table( 'inv_categories' )
                               -> join( 'inv_products' , 'inv_categories.id' , '=' , 'inv_products.category' )
                               -> selectRaw( 'inv_categories.id,inv_categories.name,inv_categories.photo,inv_categories.description' )
                               -> selectRaw( 'SUM(inv_products.balance) as stock_value' )
                               -> selectRaw( ('CASE 
                                                WHEN ((SUM(inv_products.quantity) - SUM(inv_products.sold)) / SUM(inv_products.quantity)) * 100 <= 30 THEN "low"
                                                WHEN ((SUM(inv_products.quantity) - SUM(inv_products.sold)) / SUM(inv_products.quantity)) * 100 <= 50 THEN "medium"
                                                ELSE "good" END AS status') )
                               -> groupBy( 'inv_categories.id' , 'inv_categories.name' )
                               -> get();
            $out_of_stock = DB ::table( 'inv_products' )
                               -> where( 'user_id' , '=' , $user_id )
                               -> where( 'quantity' , '<' , 1 )
                               -> count();

            return Response ::success( [
                'out_of_stock' => $out_of_stock ,
                'categories'   => $categories ,
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
                $validated      = $request -> validated();
                $uploaded_image = $request -> file( 'photo' );
                $filename       = 'public/images/' . time() . '.' . $uploaded_image -> getClientOriginalExtension();
                $image          = Image ::make( $uploaded_image );
                Storage ::put( $filename , $image -> encode() );
                $validated[ 'photo' ] = url( '/' ) . Storage ::url( $filename );
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
    }
