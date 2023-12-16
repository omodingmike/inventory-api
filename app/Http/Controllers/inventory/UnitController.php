<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreUnitRequest;
    use App\Models\inventory\Unit;

    class UnitController extends Controller
    {
        public function index ()
        {
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => Unit ::all()
            ];
        }

        public function store ( StoreUnitRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) return Response ::error( $validator -> errors() -> first() );
            $unit = Unit ::create( $request -> validated() );
            return Response ::success( $unit , 201 );
        }
    }
