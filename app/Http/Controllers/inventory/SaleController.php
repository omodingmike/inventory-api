<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\CartItem;
    use App\Models\inventory\Sale;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Arr;

    class SaleController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Sale[]|Collection|Response
         */
        public function index ()
        {
            return Sale ::with( 'data', 'contact' ) -> get();
            // all sales as of date
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store (Request $request)
        {
//            $validated = $request -> validate( [ 'amount' => 'required', 'mode' => 'required', 'product_id' => 'required', 'quantity' => 'required' ] );
            // photo,name, coategory,amount,qunatity,total,customername
//            return Sale ::create( $validated );

            $customer_id = Arr ::get( $request, 'customerID' );
            $grand_total = Arr ::get( $request, 'grandTotal' );
            $sale = Sale ::create( [
                'mode'        => 'cash',
                'sale_id'     => 'S' . time(),
                'grand_total' => $grand_total,
                'customer_id' => $customer_id
            ] );

            $items = Arr ::get( $request, 'items' );
            foreach ( $items as $item ) {
                $item[ 'sale_id' ] = $sale -> id;
                CartItem ::create( $item );
            }
            return $items;
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
