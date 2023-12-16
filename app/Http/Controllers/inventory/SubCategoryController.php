<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSubCategoryRequest;
    use App\Models\inventory\Category;
    use App\Models\inventory\SubCategory;
    use App\Traits\AWSTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class SubCategoryController extends Controller
    {
        use AWSTrait;

        public function index ( Request $request )
        {
            $validator = Validator ::make( $request -> all() , [ 'category_id' => 'required|string|exists:inv_categories,id' ] );
            if ( $validator -> fails() ) return Response ::error( $validator -> errors() -> first() );
            $validated      = $validator -> validated();
            $sub_categories = Category ::with( 'subCategories' )
                                       -> where( 'id' , $validated[ 'category_id' ] ) -> first();

            return Response ::success( $sub_categories );
        }

        public function all ()
        {
            $sub_categories = DB ::table( 'inv_sub_categories' )
                                 -> select( [ 'id' , 'name' ] )
                                 -> get();
//            foreach ( $sub_categories as $sub_category ) {
//                if ( $sub_category -> photo ) {
//                    $sub_category -> photo = $this -> getUri( $sub_category -> photo );
//                }
//            }
            if ( $sub_categories ) {
                return Response ::success( $sub_categories );
            }
            return Response ::error( 'No sub categories found' );
        }

        public function store ( StoreSubCategoryRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) return Response ::error( $validator -> errors() -> first() );
            $validated = $request -> validated();
//            $validated[ 'photo' ] = Uploads ::uploadFile( $request , 'photo' );
            $category                    = Category ::find( $validated[ 'category_id' ] );
            $sub_category                = SubCategory ::create( $validated );
            $sub_category -> category_id = $category -> id;
//            $category -> subCategories() -> syncWithoutDetaching( $sub_category -> id );
            if ( $sub_category ) return Response ::success( $sub_category );
            else return Response ::error( 'Sub category not created' );
        }
    }
