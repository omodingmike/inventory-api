<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSubCategoryRequest;
    use App\Models\inventory\SubCategory;

    class SubCategoryController extends Controller
    {
        /*
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index ()
        {
            $sub_categories = SubCategory ::all();
            if ( $sub_categories -> count() > 0 ) return Response ::success( $sub_categories );
            else return Response ::error( "No subcategories found" );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreSubCategoryRequest $request
         * @return array
         */
        public function store ( StoreSubCategoryRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated    = $request -> validated();
            $sub_category = SubCategory ::create( $validated );
            if ( $sub_category ) return Response ::success( $sub_category );
            else return Response ::error( 'Sub category not created' );
        }
    }
