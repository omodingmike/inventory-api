<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class SubCategoryTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testAllSubCategories ()
        {
            $response = $this -> json( 'GET' , '/api/subcategories' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              '*' => [
                                  'id' ,
                                  'name' ,
                              ]
                          ]
                      ] );
        }

        public function testNoSubCategories ()
        {
            $response = $this -> json( 'GET' , '/api/subcategories' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testSubCategoryStore ()
        {
            $name     = 'subcategory' . time();
            $data     = [
                'name' => $name ,
            ];
            $response = $this -> post( '/api/subcategories' , $data );
            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'name' ,
                              'id'
                          ]
                      ] );
        }

        public function testNameMissingInSubCategoryStore ()
        {
            $data     = [
            ];
            $response = $this -> post( '/api/subcategories' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
