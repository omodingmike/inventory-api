<?php

    namespace App\Http\Controllers\inventory;

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
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => SubCategory ::all()
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
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => SubCategory ::create( $request -> validate( [ 'name' => 'required|string' ] ) ) ];
        }
    }
