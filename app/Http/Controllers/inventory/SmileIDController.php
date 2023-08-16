<?php

    namespace App\Http\Controllers\inventory;

    use App\helpers\Constants;
    use App\helpers\Uploads;
    use App\Http\Controllers\Controller;
    use App\smileID\SmileIdentityCore;
    use DateTimeInterface;
    use Exception;
    use GuzzleHttp\Exception\GuzzleException;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Ouzo\Utilities\Clock;

    class SmileIDController extends Controller
    {
        public function callback ( Request $request )
        {
            info( $request );
        }

        public function files ( Request $request )
        {
            if ( $request -> hasFile( 'selfie' ) ) {
                info( $request -> file( 'selfie' ) -> getClientOriginalName() );
            } else {
                info( 'file does not exist' );
            }
//            foreach ( $request -> files as $file ) {
////                $uploaded_image = $request -> file( 'photo' );
//                info( $request );
//                $filename = 'public/images/' . time() . $file -> get . '.' . $file -> getClientOriginalExtension();
//                // Create an instance of the Intervention Image
//                $image = Image ::make( $file );
//                // Resize the image if needed
//                $image -> resize( 100 , 100 );
//                Storage ::put( $filename , $image -> encode() );
//            }
        }

        public function pathExists ( Request $request )
        {
            $data = [
                $request -> file( 'selfie' ) ,
                $request -> file( 'id' ) ,
            ];
            return $data;
        }


        public function generateSignature ()
        {
            $timestamp = Clock ::now() -> format( DateTimeInterface::ATOM );
            $message   = $timestamp . Constants::PARTNER_ID . 'sid_request';
            $signature = base64_encode( hash_hmac( 'sha256' , $message , Constants::SANDBOX_API_KEY , true ) );
            return [ 'signature' => $signature , 'timestamp' => $timestamp ];
        }

        /**
         * @throws GuzzleException*@throws \Exception
         * @throws Exception
         */
        public function submitJob ( Request $request )
        {
            $selfie = Uploads ::upload_image( $request , 'selfie' );
            $id     = Uploads ::upload_image( $request , 'id' );

            // Docs
            // https://docs.usesmileid.com/integration-options/server-to-server/php/products/document-verification
            $partner_id       = Constants::PARTNER_ID;                  // login to the Smile ID portal to view your partner id
            $default_callback = route( 'callback' );                    //callback is only required if you are running an asynchronous job
            $api_key          = Constants::PRODUCTION_API_KEY;          // copy your API key from the Smile ID portal
            $sid_server       = 1;                                      // Use '0' for the sandbox server, use '1' for production server

            $connection = new SmileIdentityCore(
                $partner_id ,
                $default_callback ,
                $api_key ,
                $sid_server
            );

// Create required tracking parameters
            $partner_params = [
                'job_id'   => 'J' . time() ,
                'user_id'  => 'U427' . time() ,
                'job_type' => 6
            ];
            $image_details  = [
                [
                    'image_type_id' => 0 ,
                    'image'         => storage_path( 'app/' . $selfie )
//                    'image'         => storage_path( 'app/images/Selfie.jpg' )
                ] ,
                [
                    'image_type_id' => 1 ,
                    'image'         => storage_path( 'app/' . $id )
//                    'image'         => storage_path( 'app/images/ID.jpg' )
                ]
            ];

            // The ID Document Information
            $id_info = [
                'country' => 'UG' ,                        // The country where ID document was issued
                'id_type' => 'NATIONAL_ID'                 // The ID document type
            ];

            // Set the options for the job
            $options = [
                'return_job_status'  => true ,  // Set to true if you want to get the job result in sync (in addition to the result been sent to your callback). If set to false, result is sent to callback url only.
                'return_history'     => false , // Set to true to return results of all jobs you have ran for the user in addition to current job result. You must set return_job_status to true to use this flag.
                'return_image_links' => true ,  // Set to true to receive selfie and liveness images you uploaded. You must set return_job_status to true to use this flag.
                'signature'          => true
            ];

            // results page
            // https://docs.usesmileid.com/products/for-individuals-kyc/document-verification#result-codes-and-result-texts
            $response = $connection -> submit_job( $partner_params , $image_details , $id_info , $options );
            // 0810 verified, 0811 failed
            $result_code = Arr ::get( $response , 'result.ResultCode' );
            $data        = [
                'register_selfie' => Arr ::get( $response , 'result.Actions.Register_Selfie' ) ,
                'verify_Document' => Arr ::get( $response , 'result.Actions.Verify_Document' ) ,
                'result_code'     => $result_code ,
                'result_text'     => Arr ::get( $response , 'result.ResultText' )
            ];
            if ( $result_code == '0810' ) {
                $data[ 'bio' ] = [
                    'FullName'       => Arr ::get( $response , 'result.FullName' ) ,
                    'DOB'            => Arr ::get( $response , 'result.DOB' ) ,
                    'IDNumber'       => Arr ::get( $response , 'result.IDNumber' ) ,
                    'ExpirationDate' => Arr ::get( $response , 'result.ExpirationDate' ) ,
                ];
            }
            return $data;
        }
    }
