<?php

    namespace App\helpers;

    class Response
    {
        public static function success ( $data ) : array
        {
            return [
                'status'  => 1 ,
                'message' => 'success' ,
                'data'    => $data
            ];
        }

        public static function error ( string $message ) : array
        {
            return [
                'status'  => 0 ,
                'message' => $message ,
                'data'    => null
            ];
        }
    }