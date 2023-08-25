<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Unit;
    use Illuminate\Http\Request;

    class UnitController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Unit[]
         */
        public function index ()
        {
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => Unit ::all()
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
                'data'    => Unit ::create( $request -> validate( [ 'name' => 'required|string' , 'symbol' => 'required|string' ] ) )
            ];
        }
    }
