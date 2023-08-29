<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreProductRequest;
    use App\Http\Requests\UpdateProductRequest;
    use App\Models\inventory\Category;
    use App\Models\inventory\Product;
    use App\Models\inventory\SubCategory;
    use App\Models\inventory\Supplier;
    use App\Models\inventory\Unit;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class ProductController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Product[]
         */
        public function index ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id  = $request -> user_id;
            $products = Product ::ofUserID( $user_id )
                                -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                                -> get();
            if ( $products -> count() > 0 ) return Response ::success( $products );
            else return Response ::error( 'No products found' );
        }

        public function details ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id              = $request -> user_id;
            $validated_product_id = Validator ::make( $request -> all() , [ 'id' => 'required|int|exists:inv_products,id' ] );
            if ( $validated_product_id -> fails() ) return Response ::error( $validated_product_id -> errors() -> first() );
            $product = Product ::ofUserID( $user_id )
                               -> ofID( $request -> id )
                               -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                               -> first();
            if ( !$product ) return Response ::error( 'No products found' );
            else return Response ::success( $product );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreProductRequest $request
         * @return array
         */
        public function store ( StoreProductRequest $request )
        {
            DB ::beginTransaction();
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $store_data              = $request -> validated();
            $store_data[ 'photo' ]   = Uploads ::upload_image( $request , 'photo' );
            $store_data              = $this -> getIDsFromNames( $request , $store_data );
            $store_data[ 'balance' ] = $request -> quantity * $request -> retail_price;
            $product                 = Product ::create( $store_data );
            DB ::commit();
            if ( $product ) return Response ::success( $product );
            else return Response ::error( 'Product not created' );
        }

        /**
         * @param       $request
         * @param array $data
         * @return array
         */
        public function getIDsFromNames ( $request , array $data ) : array
        {
            $data[ 'supplier' ]     = Supplier ::where( 'name' , $request -> supplier ) -> first() -> id;
            $data[ 'category' ]     = Category ::where( 'name' , $request -> category ) -> first() -> id;
            $data[ 'sub_category' ] = SubCategory ::where( 'name' , $request -> sub_category ) -> first() -> id;
            $data[ 'units' ]        = Unit ::where( 'name' , $request -> units ) -> first() -> id;
            return $data;
        }

        public function filterProducts ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $date_range_validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'bail|required|date' ,
                    'to'   => 'bail|required|date' ,
                ]
            );
            if ( $date_range_validator -> fails() ) return Response ::error( $date_range_validator -> errors() -> first() );
            $user_id    = $request -> user_id;
            $start_date = Carbon ::parse( $request -> query( 'from' ) ) -> copy() -> startOfDay();
            $end_date   = Carbon ::parse( $request -> query( 'to' ) ) -> copy() -> endOfDay();
            $products   = Product ::ofUserID( $user_id )
                                  -> duration( $start_date , $end_date )
                                  -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                                  -> where( 'category' , $request -> query( 'productCategory' ) )
                                  -> get();
            if ( $products -> count() < 1 ) return Response ::error( "No products found" );
            $total_quantity = 0;
            $total_balance  = 0;
            foreach ( $products as $product ) {
                $total_quantity += $product -> quantity;
                $total_balance  += $product -> balance;
            }
            $data = [
                'total_quantity' => $total_quantity ,
                'total_balance'  => $total_balance ,
                'products'       => $products
            ];
            return Response ::success( $data );
        }

        /**
         * Update the specified resource in storage.
         *
         * @param UpdateProductRequest $request
         * @return string[]
         */
        public function update ( UpdateProductRequest $request )
        {
            DB ::beginTransaction();
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $product     = Product ::find( $request -> validated()[ 'id' ] );
            $update_data = $request -> validated();
            $update_data = $this -> getIDsFromNames( $request , $update_data );
            $updated     = $product -> update( $update_data );
            DB ::commit();
            if ( $updated ) return Response ::success( $updated );
            else return Response ::error( 'Product update failed' );
        }
    }
