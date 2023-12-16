<?php

    namespace App\Traits;

    use Aws\S3\S3Client;

    trait AWSTrait
    {
        public function getUri ( string $file_path ) : string
        {
            $awsConfig = [
                'version' => '2006-03-01' ,
                'region'  => 'us-east-1' ,
            ];
            $s3Client  = new S3Client( $awsConfig );
            $command   = $s3Client -> getCommand( 'GetObject' , [
                'Bucket' => env( 'AWS_BUCKET' ) ,
                'Key'    => $file_path ,
            ] );
            $request   = $s3Client -> createPresignedRequest( $command , '+480 minutes' );
            return (string) $request -> getUri();

        }
    }