<?php

    namespace App\Http\Controllers\inventory;

    use App\Http\Controllers\Controller;
    use App\Models\inventory\Contact;
    use App\Models\inventory\Sale;
    use Error;
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
                $sales         = Sale ::ofUserID( $user_id )
                                      -> with( 'saleItems' )
                                      -> get();
                $contacts_data = [];
                $contacts      = Contact ::ofUserID( $user_id ) -> get();
                foreach ( $contacts as $contact ) {
                    $total_products = 0;
                    foreach ( $sales as $sale ) {
                        foreach ( $sale -> saleItems as $sale_item ) {
                            if ( $sale -> contact_id == $contact -> id ) {
                                $total_products += $sale_item -> quantity;
                            }
                        }
                    }
                    $contact_collection = collect( $contact );
                    $contact_collection -> put( 'products' , $total_products );
                    $contacts_data[] = $contact_collection;
                }
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $contacts_data
                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => [
                        'file' => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line' => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
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
            $contacts = Contact ::create( $request -> all() );
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => $contacts
                ];

            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => [
                        'file' => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line' => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
                ];
            }
        }

        public function update ( Request $request )
        {
            try {
                return [
                    'status'  => 1 ,
                    'message' => 'success' ,
                    'data'    => [ 'updated' => ( Contact ::find( $request -> id ) )
                        -> update( $request -> except( 'id' ) ) ]
                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 0 ,
                    'message' => $exception -> getMessage() ,
                    'data'    => [
                        'Error' => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'file'  => $exception -> getTrace()[ 0 ] [ 'file' ] ,
                        'line'  => $exception -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
                ];
            }
            catch ( Error $error ) {
                return [
                    'status'  => 0 ,
                    'message' => $error -> getMessage() ,
                    'data'    => [
                        'file' => $error -> getTrace()[ 0 ] [ 'file' ] ,
                        'line' => $error -> getTrace()[ 0 ] [ 'line' ] ,
                    ]
                ];
            }

        }
    }
