<?php

    namespace App\smileID;

    use DateTimeInterface;
    use Ouzo\Utilities\Clock;

    class Signature
    {
        private string $api_key;
        private string $partner_id;
        private string $timestamp;

        /**
         * Signature constructor.
         * @param $partner_id
         * @param $api_key
         */
        function __construct ( $partner_id , $api_key )
        {
            $this -> api_key    = $api_key;
            $this -> partner_id = $partner_id;
            $this -> timestamp  = Clock ::now() -> getTimestamp();
//            $this -> timestamp  = date( 'Y-m-d\TH:i:sP' , time() );
//            $this -> timestamp = ( new DateTime() ) -> getTimestamp();
        }

        /**
         * Confirms the signature against a newly generated signature based on the same timestamp
         * @param        $timestamp
         * @param string $signature
         * @return bool
         */
        function confirm_signature ( $timestamp , string $signature ) : bool
        {
            return $signature === $this -> generate_signature( $timestamp )[ "signature" ];
        }

        /**
         * Generates a signature for the provided timestamp or the current timestamp by default
         * @param $timestamp
         * @return array
         */
        function generate_signature ( $timestamp = null ) : array
        {
            $timestamp = $timestamp != null ? $timestamp : Clock ::now() -> format( DateTimeInterface::ATOM );
//            $timestamp = ( new DateTime() ) -> getTimestamp();
            $message   = $timestamp . $this -> partner_id . "sid_request";
            $signature = base64_encode( hash_hmac( 'sha256' , $message , $this -> api_key , true ) );
            return [ "signature" => $signature , "timestamp" => $timestamp ];
        }

        /**
         * @param $timestamp
         * @return bool
         */
        private function isTimestamp ( $timestamp ) : bool
        {
            if ( ctype_digit( $timestamp ) && strtotime( date( 'Y-m-d H:i:s' , $timestamp ) ) === (int) $timestamp ) {
                return true;
            } else {
                return false;
            }
        }
    }