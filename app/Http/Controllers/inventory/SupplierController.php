<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSupplierRequest;
    use App\Models\inventory\Supplier;

    class SupplierController extends Controller
    {
        public function index ()
        {
            $supplier = Supplier ::all();
            if ( $supplier -> count() > 0 ) return Response ::success( $supplier );
            else return Response ::error( 'No Suppliers found' );
        }

        public function store ( StoreSupplierRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated            = $request -> validated();
            $validated[ 'photo' ] = Uploads ::uploadFile( $request , 'photo' );
            $supplier             = Supplier ::create( $validated );
            if ( $supplier ) return Response ::success( $supplier );
            else return Response ::error( 'Supplier could not be created' );
        }
    }
