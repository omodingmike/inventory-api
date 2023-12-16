<?php

    namespace App\Http\Controllers\Auth;

    use App\helpers\Response;
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use App\Rules\Phone;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;

    class AuthController extends Controller
    {
        public function login ( Request $request )
        {
            $user = DB ::table( 'users' ) -> where( 'email' , $request -> phone ) -> first();

            if ( $user && Hash ::check( $request -> pin , $user -> password ) ) {
                return Response ::success( $user );
            } else {
                return Response ::error( 'Invalid Login credentials' );
            }
        }

        public function register ( Request $request )
        {
            $validator = validator( $request -> all() , [
                'phone' => [ 'required' , 'unique:users,email' , new Phone() ] ,
                'pin'   => 'required|min:4|max:4' ,
            ] );
            if ( $validator -> fails() ) return Response ::error( $validator -> errors() -> first() );
            $user = User ::create( [
                'email'    => $request -> phone ,
                'password' => Hash ::make( $request -> pin ) ,
            ] );
            if ( $user ) {
                return Response ::success( $user );
            } else {
                return Response ::error( 'Something wrong happened' );
            }
        }
    }
