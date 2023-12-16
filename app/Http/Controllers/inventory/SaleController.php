<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreSaleRequest;
    use App\Models\inventory\CartItem;
    use App\Models\inventory\Product;
    use App\Models\inventory\Sale;
    use App\Traits\DateTrait;
    use App\Traits\UserTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Validator;

    class SaleController extends Controller
    {
        use UserTrait , DateTrait;

        public function index ( Request $request )
        {
            DB ::enableQueryLog();
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id = $this -> userID( $request );

            if ( !empty( $this -> from( $request ) ) && !empty( $this -> to( $request ) ) ) {
                $date_range_errors = $this -> validateDate( $request );
                if ( $date_range_errors ) return Response ::error( $date_range_errors );
                [ $start_date , $end_date ] = $this -> dateRange( $request );

                $sales = Sale ::ofUserID( $user_id )
                              -> with( 'customer' , 'saleItems.product' )
                              -> whereBetween( 'inv_sales.created_at' , [ $start_date , $end_date ] );

//                $sales = DB ::table( 'inv_sales' )
//                            -> select( 'inv_sales.id' , 'inv_sales.sale_id' , 'inv_sales.payment_mode AS mode' , 'inv_sales.grand_total' ,
//                                DB ::raw( 'DATE_FORMAT(inv_sales.created_at, "%D %b %Y %h:%i% %p") as created_at' ) )
//                            -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
//                            -> where( 'inv_sales.user_id' , '=' , $user_id )
//                            -> whereBetween( 'inv_sales.created_at' , [ $start_date , $end_date ] )
//                            -> groupBy( 'inv_sales.id' , 'inv_sales.sale_id' , 'inv_sales.created_at' , 'inv_sales.payment_mode' , 'inv_sales.grand_total' );


            } else {
//                $sales = DB ::table( 'inv_sales' )
//                            -> select( 'inv_sales.id' , 'inv_sales.sale_id' , 'inv_sales.payment_mode AS mode' , 'inv_sales.grand_total' ,
//                                DB ::raw( 'DATE_FORMAT(inv_sales.created_at, "%D %b %Y %h:%i% %p") as created_at' ) )
//                            -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
//                            -> where( 'inv_sales.user_id' , '=' , $user_id )
//                            -> groupBy( 'inv_sales.id' , 'inv_sales.sale_id' , 'inv_sales.created_at' );
//
                $sales = Sale ::ofUserID( $user_id )
                              -> with( 'customer' , 'saleItems.product' );

            }
            $sold_products = 0;
            foreach ( $sales -> get() as $sale ) {
                foreach ( $sale -> saleItems as $sale_item ) {
                    $sold_products += $sale_item -> quantity;
                }
            }

            info( DB ::getQueryLog() );

            $data = [
//                'products_sold' => (int) $sales -> sum( 'inv_cart_items.quantity' ) ,
                'products_sold' => $sold_products ,
                'sales'         => $sales -> orderBy( 'created_at' , 'desc' ) -> get()
            ];
            return Response ::success( $data );
        }

        public function show ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id = $this -> userID( $request );

            $validate_saleID = Validator ::make( $request -> all() , [ 'sale_id' => 'bail|required|string|exists:inv_sales,sale_id' ] );
            if ( $validate_saleID -> stopOnFirstFailure() -> fails() ) {
                return Response ::error( $validate_saleID -> messages() -> first() );
            }

            $sale_details = Sale ::where( [ 'user_id' => $user_id , 'sale_id' => $request -> sale_id ] )
                                 -> with( 'customer' )
                                 -> with( 'saleItems.product' ) -> first();
//            $sale         = DB ::table( 'inv_sales' )
//                               -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
//                               -> join( 'inv_contacts' , 'inv_sales.contact_id' , '=' , 'inv_contacts.id' )
//                               -> selectRaw( 'inv_sales.id AS order_no,inv_sales.created_at AS date,inv_contacts.name AS customer_name, inv_sales.payment_mode AS payment_method' )
//                               -> selectRaw( 'inv_sales.sale_id AS sale_id,inv_sales.grand_total' )
//                               -> where( 'inv_sales.user_id' , '=' , $user_id )
//                               -> where( 'inv_sales.sale_id' , '=' , $request -> sale_id )
//                               -> get();
//            $products     = DB ::table( 'inv_sales' )
//                               -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
//                               -> join( 'inv_products' , 'inv_cart_items.product_id' , '=' , 'inv_products.id' )
//                               -> selectRaw( 'inv_products.name,inv_products.name,inv_cart_items.total' )
//                               -> where( 'inv_sales.user_id' , '=' , $user_id )
//                               -> where( 'inv_sales.sale_id' , '=' , $request -> sale_id )
//                               -> get();
//            $details      = collect( [ $sale , $products ] );
//            $sale_details->m

            if ( $sale_details ) return Response ::success( $sale_details );
            else return Response ::error( 'No details found' );
        }

        public function store ( StoreSaleRequest $request )
        {
            DB ::beginTransaction();
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated               = $request -> validated();
            $user_id                 = $validated [ 'user_id' ];
            $validated [ 'sale_id' ] = 'S' . time();
            $sale                    = Sale ::create( $validated );

            foreach ( $validated [ 'items' ] as $item ) {
                $item[ 'sale_id' ] = $sale -> id;
                $product           = Product ::ofUserID( $user_id )
                                             -> where( 'name' , $item [ 'name' ] )
                                             -> first();
                if ( $product -> quantity < $item [ 'quantity' ] ) {
                    DB ::rollBack();
                    $product_name     = $product -> name;
                    $product_quantity = $product -> quantity;
                    return Response ::error( "Only $product_quantity items in stock for $product_name " );
                }

                if ( !$product ) {
                    DB ::rollBack();
                    return Response ::error( 'Products not for this user' );
                }
                $item[ 'product_id' ] = $product -> id;
                $product -> increment( 'sold' , $item [ 'quantity' ] );
                $product -> decrement( 'quantity' , $item [ 'quantity' ] );
                CartItem ::create( $item );
            }
            DB ::commit();
            $sales = Sale ::with( 'saleItems.product' ) -> where( 'sale_id' , $sale -> sale_id ) -> first();

            return Response ::success( $sales , 200 );
        }
    }
