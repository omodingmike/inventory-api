<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Category;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Storage;
    use Intervention\Image\Facades\Image;
    use LaravelIdea\Helper\App\Models\_IH_Category_C;


    class CategoryController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return _IH_Category_C|Category[]|Collection
         */
        public function index ()
        {
            return Category ::with( 'products.supplier', 'products.units' ) -> get();
        }

        public function getCategoryProducts ()
        {
            return [
                'status'  => 1,
                'message' => 'success',
                'data'    => Category ::with( 'products.supplier', 'products.units' ) -> get()

            ];
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store (Request $request)
        {
            $validated = $request -> validate( [ 'name' => 'required|string', 'photo' => 'required|image' ] );
            $uploaded_image = $request -> file( 'photo' );
            $filename = 'public/images/' . time() . '.' . $uploaded_image -> getClientOriginalExtension();
            $image = Image ::make( $uploaded_image );
            $image -> resize( 100, 100 );
            Storage ::put( $filename, $image -> encode() );
            $validated[ 'photo' ] = url( '/' ) . Storage ::url( $filename );
            $category = Category ::create( $validated );
            if ( $category ) {
                return [
                    'status'  => 'ok',
                    'message' => 'success'
                ];
            } else {
                return [
                    'status'  => 'failed',
                    'message' => 'Contact could not be created'
                ];
            }
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return Response
         */
        public function show ($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         * @return Response
         */
        public function update (Request $request, $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return Response
         */
        public function destroy ($id)
        {
            //
        }
    }
