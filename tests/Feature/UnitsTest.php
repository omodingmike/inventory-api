<?php

    namespace Tests\Feature;

    use Exception;
    use Tests\TestCase;

    class UnitsTest extends TestCase
    {
//        use RefreshDatabase;


        public function testAllUnits ()
        {
            $response = $this -> get( '/api/units' ); // Assuming the endpoint is /api/data
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              '*' => [
                                  'id' ,
                                  'name' ,
                                  'symbol' ,
                              ]
                          ]
                      ] );
        }

        public function testNoUnits ()
        {
            $response = $this -> get( '/api/units' ); // Assuming the endpoint is /api/data
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        /**
         * @throws Exception
         */
        public function testUnitStore ()
        {
            $postData = [
                'name'   => 'name' . time() ,
                'symbol' => 'L' . time()
            ];
            $response = $this -> json( 'POST' , '/api/units' , $postData );
            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'name' ,
                              'symbol' ,
                              'id'
                          ]
                      ] );
        }

        public function testNameMissingInUnitStore ()
        {
            $postData = [
                'symbol' => 'L' . time()
            ];
            $response = $this -> json( 'POST' , '/api/units' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );

        }

        public function testSymbolMissingInUnitStore ()
        {
            $postData = [
                'name' => 'L' . time()
            ];
            $response = $this -> json( 'POST' , '/api/units' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );

        }
    }
