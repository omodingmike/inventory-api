<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\SubCategory;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class SubCategoryController extends Controller
    {
        /*
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index ()
        {
            return SubCategory ::all();
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store ( Request $request )
        {
            return SubCategory ::create( $request -> validate( [ 'name' => 'required|string' ] ) );
        }
    }
