<?php

    namespace App\helpers;

    use Illuminate\Http\Request;

    class Uploads
    {
        public static function uploadFile ( Request $request , string $key ) : string
        {
//            $uploaded_image = $request -> file( $key );
////            $filename       = 'public/images/' . time() . '.' . $uploaded_image -> getClientOriginalExtension();
//            $filename = 'images/' . time() . '_' . $uploaded_image -> getClientOriginalName();
//            // Create an instance of the Intervention Image
//            $image = Image ::make( $uploaded_image );
//            // Resize the image if needed
////            $image -> resize( 100 , 100 );
//            $path = Storage ::disk( 'public' ) -> put( $filename , $image -> encode() );
//            info( $path );
////        $validated['photo'] = url('/') . Storage::url($filename);
////            return url( '/' ) . Storage ::url( $filename );
////            return Storage ::url( $filename );
//            return $filename;

//            if ( !$request -> hasFile( $key ) ) {
//                $photos = [
//                    'inv_images/1699353994_inventory_1.png' ,
//                    'inv_images/1699354039_inventory_2.png' ,
//                    'inv_images/1699354056_Inventory_3.png' ,
//                    'inv_images/1699354073_inventory_4.png' ,
//                    'inv_images/1699354091_Inventory_5.png' ,
//                    'inv_images/1699354108_Inventory_6.png' ,
//                    'inv_images/1699354126_inventory_7.png' ,
//                    'inv_images/1699354142_inventory_8.png' ,
//                    'inv_images/1699354161_inventory_9.png' ,
//                    'inv_images/1699354178_inventory_10.png'
//                ];
//                return $photos[ rand( 0 , count( $photos ) - 1 ) ];
//            }

            $uploaded_file = $request -> file( $key );
            $file_name     = time() . '_' . $uploaded_file -> getClientOriginalName();
            $relative_path = 'inv_images';
            $base_path     = '/' . $relative_path;
            $uploaded_file -> storeAs( $base_path , $file_name );
            return $relative_path . '/' . $file_name;
        }

        public static function storeFile ( Request $request , string $key ) : string
        {
            return basename( $request -> file( $key ) -> store( 'public' ) );
        }
    }
