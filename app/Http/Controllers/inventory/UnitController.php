<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Unit;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class UnitController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Collection|Unit[]
         */
        public function index ()
        {
            return Unit ::all();
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store ( Request $request )
        {
            return Unit ::create( $request -> validate( [ 'name' => 'required|string' , 'symbol' => 'required|string' ] ) );
        }
    }
