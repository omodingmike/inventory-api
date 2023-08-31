<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreContactRequest;
    use App\Http\Requests\UpdateContactRequest;
    use App\Models\inventory\Contact;
    use App\Models\inventory\Sale;
    use App\Models\User;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ContactController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return JsonResponse
         */
        public function index ( Request $request )
        {
            $errors = User ::validateUserId( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id       = $request -> user_id;
            $sales         = Sale ::ofUserID( $user_id )
                                  -> with( 'saleItems' )
                                  -> get();
            $contacts_data = [];
            $contacts      = Contact ::ofUserID( $user_id ) -> get();
            if ( $contacts -> count() < 1 ) {
                return Response ::error( 'No contacts found for this userId' );
            }
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
            return Response ::success( $contacts_data );
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StoreContactRequest $request
         * @return JsonResponse
         */
        public function store ( StoreContactRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $validated = $request -> validated();
            $contact   = Contact ::create( $validated );
            if ( $contact ) {
                return Response ::success( $contact , 201 );
            } else {
                return Response ::error( 'Contact could not be created' );
            }
        }

        public function update ( UpdateContactRequest $request )
        {
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }
            $updated = Contact ::find( $request -> validated()[ 'id' ] )
                               -> update( $request -> except( 'id' ) );
            if ( $updated ) return Response ::success( 'Contact Updated successfully' );
            else return Response ::error( 'Contact could not be updated' );
        }
    }
