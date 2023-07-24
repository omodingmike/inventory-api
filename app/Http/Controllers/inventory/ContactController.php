<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Contact;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use LaravelIdea\Helper\App\Models\_IH_Contact_C;

    class ContactController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return _IH_Contact_C|Collection|Contact[]
         */
        public function index ()
        {
            return Contact ::with( 'sales.data' ) -> get();
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store (Request $request)
        {
            $contact = Contact ::create( $request -> all() );
            if ( $contact ) {
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
