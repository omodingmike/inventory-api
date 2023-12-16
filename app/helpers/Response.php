<?php

    namespace App\helpers;

    use Illuminate\Http\JsonResponse;

    class Response
    {
        public static function success ( $data , int $status = 200 ) : JsonResponse
        {
            return response() -> json( [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => $data
            ] , $status ) -> withHeaders( [
                'PHP-version' => phpversion()
            ] );
        }

        public static function error ( string $message ) : JsonResponse
        {
            //            info( $response );
            return response() -> json( [
                'status'  => 0 ,
                'message' => $message ,
                'data'    => null
            ] );
        }
    }
    