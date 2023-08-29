<?php

    namespace App\helpers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class CustomValidator
    {
        public static function validateUserId ( Request $request )
        {
            info( $request -> get( 'user_id' ) );
            return Validator ::make( $request -> all() ,
                [
                    'user_id' => 'bail|required|int|exists:users,id'
                ]
                , [
                    'required' => 'user_id not found in request' ,
                    'int'      => 'user_id should be an integer' ,
                    'exists'   => "user with given ID not found"
                ]
            );
        }
    }