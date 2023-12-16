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
    use App\Traits\DateTrait;
    use App\Traits\UserTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class ProductController extends Controller
    {
        use DateTrait , UserTrait;

        public function index ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id = $this -> userID( $request );

            $products = Product ::ofUserID( $user_id )
                                -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                                -> get();

            if ( $products -> count() > 0 ) return Response ::success( $products );
            else return Response ::error( 'No products found' );
        }

        public function search ( Request $request )
        {
            $user_id = $this -> userID( $request );
            $name    = $request -> name;

            $products = Product ::ofUserID( $user_id )
                                -> with( 'category' )
                                -> where( 'name' , 'like' , "%$name%" )
                                -> get();

            if ( $products -> count() > 0 ) return Response ::success( $products );
            else return Response ::error( 'No products found' );
        }

        public function details ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id = $this -> userID( $request );

            $validated_product_id = Validator ::make( $request -> all() , [ 'id' => 'required|int|exists:inv_products,id' ] );

            if ( $validated_product_id -> stopOnFirstFailure() -> fails() ) return Response ::error( $validated_product_id -> errors() -> first() );

            $product = Product ::ofUserID( $user_id )
                               -> ofID( $request -> id )
                               -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                               -> first();

            if ( !$product ) return Response ::error( 'No product found' );
            else return Response ::success( $product );
        }

        public function store ( StoreProductRequest $request )
        {
            DB ::beginTransaction();
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }

            $validated                 = $request -> validated();
            $validated[ 'photo' ]      = Uploads ::uploadFile( $request , 'photo' );
            $product_data              = $this -> getIDsFromNames( $request , $validated );
            $product_data[ 'balance' ] = ($request -> quantity) * ($request -> retail_price);
            $product                   = Product ::create( $product_data );

            DB ::commit();
            if ( $product ) return Response ::success( $product );
            else return Response ::error( 'Product not created' );
        }

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
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id  = $this -> userID( $request );
            $category = $request -> query( 'category' );

            if ( !empty( $this -> from( $request ) ) && !empty( $this -> to( $request ) ) ) {
                [ $start_date , $end_date ] = $this -> dateRange( $request );
                $products = Product ::ofUserID( $user_id )
                                    -> duration( $start_date , $end_date )
                                    -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                                    -> where( 'category' , $category )
                                    -> get();

            } else {
                $products = Product ::ofUserID( $user_id )
                                    -> with( 'supplier' , 'units' , 'category' , 'subCategory' )
                                    -> where( 'category' , $category )
                                    -> get();
            }

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

        public function update ( UpdateProductRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }

            $product     = Product ::find( $request -> validated()[ 'id' ] );
            $update_data = $request -> validated();
            $update_data = $this -> getIDsFromNames( $request , $update_data );
            $updated     = $product -> update( $update_data );
            if ( $updated ) return Response ::success( $updated );
            else return Response ::error( 'Product update failed' );
        }
    }
