<?php

    namespace App\Traits;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    trait UserTrait
    {
        public function validateUserID ( Request $request )
        {
            $validator = Validator ::make( $request -> all() , [ 'user_id' => 'bail|required|int|exists:users,id' ] );
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                return $validator -> messages() -> first();
            }
            return null;
        }

        public function userID ( Request $request )
        {
            return $request -> user_id;
        }

    }