<?php

    namespace App\helpers;

    use Illuminate\Http\JsonResponse;

    class Response
    {
        public static function success ( $data , int $status = 200 ) : JsonResponse
        {
            $response = response() -> json( [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => $data
            ] , $status );
            info( $response );
            return $response;
        }

        public static function error ( string $message ) : JsonResponse
        {
            $response = response() -> json( [
                'status'  => 0 ,
                'message' => $message ,
                'data'    => null
            ] );
            info( $response );
            return $response;
        }
    }