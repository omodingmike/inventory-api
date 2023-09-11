<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSubCategoryRequest;
    use App\Models\inventory\Category;
    use App\Models\inventory\SubCategory;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class SubCategoryController extends Controller
    {
        /*
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index ( Request $request )
        {
            $validator = Validator ::make( $request -> all() , [ 'category_id' => 'required|string|exists:inv_categories,id' ] );
            if ( $validator -> fails() ) return Response ::error( $validator -> errors() -> first() );
            $validated = $validator -> validated();
            $data      = Category ::with( 'subCategories' )
                                  -> where( 'id' , $validated[ 'category_id' ] ) -> get();
            return Response ::success( $data );
//            $sub_categories = SubCategory ::all();
//            if ( $sub_categories -> count() > 0 ) return Response ::success( $sub_categories );
//            else return Response ::error( "No subcategories found" );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreSubCategoryRequest $request
         * @return JsonResponse
         */
        public function store ( StoreSubCategoryRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated    = $request -> validated();
            $category     = Category ::find( $validated[ 'category_id' ] );
            $sub_category = SubCategory ::create( $validated );
            $category -> subCategories() -> syncWithoutDetaching( $sub_category -> id );
            if ( $sub_category ) return Response ::success( $sub_category , 201 );
            else return Response ::error( 'Sub category not created' );
        }
    }
