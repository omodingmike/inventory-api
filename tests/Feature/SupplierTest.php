<?php

    namespace Tests\Feature;

    use Illuminate\Http\UploadedFile;
    use Tests\TestCase;

    class SupplierTest extends TestCase
    {


        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testAllSuppliers ()
        {
            $response = $this -> json( 'GET' , '/api/suppliers' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              '*' => [
                                  'id' ,
                                  'name' ,
                                  'photo'
                              ]
                          ]
                      ] );
        }

        public function testNoSuppliers ()
        {
            $response = $this -> json( 'GET' , '/api/suppliers' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testSupplierStore ()
        {
            $name     = 'category' . time();
            $data     = [
                'name'  => $name ,
                'photo' => UploadedFile ::fake() -> image( 'product.jpg' )
            ];
            $response = $this -> post( '/api/suppliers' , $data );
            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'name' ,
                              'photo' ,
                              'id'
                          ]
                      ] );
        }

        public function testNameMissingInSupplierStore ()
        {
            $data     = [
                'photo' => UploadedFile ::fake() -> image( 'product.jpg' )
            ];
            $response = $this -> post( '/api/suppliers' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function tesPhotoMissingInSupplierStore ()
        {
            $data     = [
                'name' => 'name' . time()
            ];
            $response = $this -> post( '/api/suppliers' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
