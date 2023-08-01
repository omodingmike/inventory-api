<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Uploads;
    use App\Models\inventory\Supplier;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use LaravelIdea\Helper\App\Models\_IH_Supplier_C;

    class SupplierController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Collection|Supplier[]
         */
        public function index ()
        {
            return Supplier ::all();
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store ( Request $request )
        {
            $validated            = $request -> validate( [ 'name' => 'required|string' , 'photo' => 'required|image' , ] );
            $validated[ 'photo' ] = Uploads ::upload_image( $request );
            return Supplier ::create( $validated );
        }
    }
