<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSupplierRequest;
    use App\Models\inventory\Supplier;
    use Illuminate\Http\JsonResponse;

    class SupplierController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return JsonResponse
         */
        public function index ()
        {
            $supplier = Supplier ::all();
            if ( $supplier -> count() > 0 ) return Response ::success( $supplier );
            else return Response ::error( 'No Suppliers found' );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreSupplierRequest $request
         * @return JsonResponse
         */
        public function store ( StoreSupplierRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated            = $request -> validated();
            $validated[ 'photo' ] = Uploads ::upload_image( $request , 'photo' );
            $supplier             = Supplier ::create( $validated );
            if ( $supplier ) return Response ::success( $supplier , 201 );
            else return Response ::error( 'Supplier could not be created' );
        }
    }
