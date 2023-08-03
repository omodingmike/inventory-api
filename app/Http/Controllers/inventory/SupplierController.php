<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Models\inventory\Supplier;
    use Illuminate\Http\Request;

    class SupplierController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Supplier[]
         */
        public function index ()
        {
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => Supplier ::all() ];
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return array
         */
        public function store ( Request $request )
        {
            $validated            = $request -> validate( [ 'name' => 'required|string' , 'photo' => 'required|image' , ] );
            $validated[ 'photo' ] = Uploads ::upload_image( $request );
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => Supplier ::create( $validated ) ];
        }
    }
