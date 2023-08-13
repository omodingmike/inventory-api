<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Constants;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Signature;

    class SmileIDController extends Controller
    {
        public function generateSignature ()
        {
            return ( new Signature( Constants::PARTNER_ID , Constants::SANDBOX_API_KEY ) )
                -> generate_signature( time() );
        }

        public function callback ( Request $request )
        {
            $signature = new Signature( Constants::PARTNER_ID , Constants::SANDBOX_API_KEY );

// Confirm a signature
//            $signature = $signature -> confirm_signature( '<put the received timestamp>' , '<put the received signature>' );
            info( $request );
        }
    }
