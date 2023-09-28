<?php

    namespace Tests\Feature;

    use App\helpers\TestingModels;
    use Illuminate\Http\UploadedFile;
    use Tests\TestCase;

    class CategoryTest extends TestCase
    {

        public function testAllCategories ()
        {
            TestingModels ::createModels();
            $response = $this -> json( 'GET' , "/api/categories?user_id=1" );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'out_of_stock' ,
                              'categories' => [
                                  '*' => [
                                      'id' ,
                                      'name' ,
                                      'description' ,
                                      'photo' ,
                                      'stock_value' ,
                                      'status' ,
                                  ]
                              ]
                          ]
                      ] );
        }

        public function testNoCategories ()
        {
            $response = $this -> get( '/api/categories' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function userIdMissingInGetAllCategories ()
        {
            $response = $this -> json( 'GET' , '/api/categories' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testCategoryStore ()
        {
            $name     = 'category' . time();
            $data     = [
                'name'        => $name ,
                'user_id'     => 1 ,
                'photo'       => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'description' => 'Description' ,
            ];
            $response = $this -> post( '/api/categories' , $data );
            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'name' ,
                              'photo' ,
                              'user_id' ,
                              'description' ,
                              'id'
                          ]
                      ] );

        }

        public function testNameMissingInCategoryStore ()
        {
            $data     = [
                'user_id'     => 1 ,
                'photo'       => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'description' => 'Description' ,
            ];
            $response = $this -> post( '/api/categories' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInCategoryStore ()
        {
            $data     = [
                'name'        => 'category' . time() ,
                'photo'       => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'description' => 'Description' ,
            ];
            $response = $this -> post( '/api/categories' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );


        }

        public function testPhotoMissingInCategoryStore ()
        {
            $data     = [
                'name'        => 'category' . time() ,
                'user_id'     => 1 ,
                'description' => 'Description' ,
            ];
            $response = $this -> post( '/api/categories' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testDescriptionMissingInCategoryStore ()
        {
            $data     = [
                'name'    => 'category' . time() ,
                'user_id' => 1 ,
                'photo'   => UploadedFile ::fake() -> image( 'product.jpg' ) ,
            ];
            $response = $this -> post( '/api/categories' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
