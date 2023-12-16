<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreContactRequest;
    use App\Http\Requests\UpdateContactRequest;
    use App\Models\inventory\Contact;
    use App\Traits\UserTrait;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class ContactController extends Controller
    {
        use UserTrait;

        public function index ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );

            $user_id = $this -> userID( $request );

//            $sales = DB ::table( 'inv_sales' )
//                        -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
//                        -> join( 'inv_contacts' , 'inv_sales.contact_id' , '=' , 'inv_contacts.id' )
//                        -> get();

//            $contacts_data = [];
//            $contacts      = Contact ::ofUserID( $user_id ) -> get();
//            if ( $contacts -> count() < 1 ) {
//                return Response ::error( 'No contacts found for this userId' );
//            }
//            foreach ( $contacts as $contact ) {
//                $total_products = 0;
//                foreach ( $sales as $sale ) {
//                    foreach ( $sale -> saleItems as $sale_item ) {
//                        if ( $sale -> contact_id == $contact -> id ) {
//                            $total_products += $sale_item -> quantity;
//                        }
//                    }
//                }
//                $contact_collection = collect( $contact );
//                $contact_collection -> put( 'products' , $total_products );
//                $contacts_data[] = $contact_collection;
//            }
            $contacts = DB ::table( 'inv_contacts' )
                           -> select( 'inv_contacts.id' , 'inv_contacts.name' , 'inv_contacts.phone' , 'inv_contacts.email' ,
                               DB ::raw( 'CAST(SUM(inv_cart_items.quantity)  AS UNSIGNED) as products' )
                           )
                           -> leftJoin( 'inv_sales' , 'inv_contacts.id' , '=' , 'inv_sales.contact_id' )
//                           -> join( 'inv_sales' , 'inv_contacts.id' , '=' , 'inv_sales.contact_id' )
//                           -> join( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
                           -> leftJoin( 'inv_cart_items' , 'inv_sales.id' , '=' , 'inv_cart_items.sale_id' )
                           -> groupBy( 'inv_contacts.id' , 'inv_contacts.name' , 'inv_contacts.phone' , 'inv_contacts.email' )
                           -> where( 'inv_contacts.user_id' , '=' , $user_id )
                           -> get();
            foreach ( $contacts as $contact ) {
                if ( $contact -> products == null ) {
                    $contact -> products = 0;
                }
            }
            return Response ::success( $contacts );
        }

        public function all ( Request $request )
        {
            $errors = $this -> validateUserID( $request );
            if ( $errors ) return Response ::error( $errors );
            $user_id  = $this -> userID( $request );
            $contacts = DB ::table( 'inv_contacts' )
                           -> where( 'user_id' , $user_id )
                           -> get();
            return Response ::success( $contacts );
        }

        public function store ( StoreContactRequest $request )
        {
            info( $request );
            $validator = $request -> validator;
            if ( $validator -> fails() ) {
                return Response ::error( $validator -> errors() -> first() );
            }

            $validated = $request -> validated();
            $contact   = Contact ::create( $validated );
            $contacts  = DB ::table( 'inv_contacts' )
                            -> where( 'user_id' , $validated[ 'user_id' ] )
                            -> get();
            if ( $contact ) {
                return Response ::success( $contacts );
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
