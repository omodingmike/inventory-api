<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Contact;
    use App\Models\inventory\Sale;
    use Exception;
    use Illuminate\Http\Request;

    class ContactController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Contact[]
         */
        public function index ( Request $request )
        {
            $user_id = $request -> user_id;
            try {
                $sales    = Sale ::ofUserID( $user_id )
                                 -> with( 'saleItems' )
                                 -> get();
                $data     = [];
                $contacts = Contact ::ofUserID( $user_id ) -> get();
                foreach ( $contacts as $contact ) {
                    $total_products = 0;
                    foreach ( $sales as $sale ) {
                        foreach ( $sale -> saleItems as $item ) {
                            if ( $sale -> contact_id == $contact -> id ) {
                                $total_products += $item -> quantity;
                            }
                        }
                    }
                    $collection = collect( $contact );
                    $collection -> put( 'products' , $total_products );
                    $data[] = $collection;
                }
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $data
                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => []
                ];
            }
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store ( Request $request )
        {
            $contact = Contact ::create( $request -> all() );
            if ( $contact ) {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $contact
                ];
            } else {
                return [
                    'status'  => 0 ,
                    'message' => 'Contact could not be created' ,
                    'data'    => []
                ];
            }
        }
    }
