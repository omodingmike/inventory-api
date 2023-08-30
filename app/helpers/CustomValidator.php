<?php

    namespace App\helpers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Validator;

    class CustomValidator
    {
        public static function validateUserId ( Request $request )
        {
            return Validator ::make( $request -> all() ,
                [
                    'user_id' => 'bail|required|int|exists:users,id'
                ]
            );
        }

        public static function validateDateRange ( Request $request )
        {
            $date_range_validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'bail|required|date' ,
                    'to'   => 'bail|required|date' ,
                ]
            );
            if ( $date_range_validator -> fails() ) return Response ::error( $date_range_validator -> errors() -> first() );
        }
    }