<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\SubCategory;
    use Illuminate\Http\Request;

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
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => $sub_categories
            ];

        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return array
         */
        public function store ( Request $request )
        {
            $sub_category = SubCategory ::create( $request -> validate( [ 'name' => 'required|string' ] ) );
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => $sub_category ];
        }
    }
