<?php

    namespace Tests\Feature;

    use Exception;
    use Faker\Factory as Faker;
    use Tests\TestCase;

    class ContactTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testAllUserContacts ()
        {
            $response = $this -> json( 'GET' , '/api/contacts?user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              '*' => [
                                  'id' ,
                                  'name' ,
                                  'phone' ,
                                  'email' ,
                                  'products' ,
                              ]
                          ] ,
                      ] );
        }

        public function testUserIdMissingInAllUserContacts ()
        {
            $response = $this -> json( 'GET' , '/api/contacts' );
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
        public function testContactStore ()
        {
            $faker    = Faker ::create();
            $postData = [
                'name'    => $faker -> firstName() ,
                'phone'   => '+2567' . random_int( 10000000 , 99999999 ) ,
                'email'   => $faker -> safeEmail() ,
                'user_id' => 1 ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'name' ,
                              'phone' ,
                              'email' ,
                              'id' ,
                          ] ,
                      ] );
        }

        public function testNameMissingInContactStore ()
        {
            $faker    = Faker ::create();
            $postData = [
                'phone'   => '+2567' . random_int( 10000000 , 99999999 ) ,
                'email'   => $faker -> safeEmail() ,
                'user_id' => 1 ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testPhoneMissingInContactStore ()
        {
            $faker    = Faker ::create();
            $postData = [
                'name'    => $faker -> firstName() ,
                'email'   => $faker -> safeEmail() ,
                'user_id' => 1 ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
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
        public function testUserIdMissingInContactStore ()
        {
            $faker    = Faker ::create();
            $postData = [
                'name'  => $faker -> firstName() ,
                'phone' => '+2567' . random_int( 10000000 , 99999999 ) ,
                'email' => $faker -> safeEmail() ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
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
        public function testContactUpdate ()
        {
            $digit    = random_int( 1 , 100 );
            $postData = [
                'name'  => 'mike' ,
                'phone' => '+2567' . random_int( 10000000 , 99999999 ) ,
                'email' => "norris07@example$digit.org" ,
                'id'    => 1 ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testNameMissingInContactUpdate ()
        {
            $digit    = random_int( 1 , 100 );
            $postData = [
                'phone' => '+2567' . random_int( 10000000 , 99999999 ) ,
                'email' => "norris07@example$digit.org" ,
                'id'    => 1 ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
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
        public function testPhoneMissingInContactUpdate ()
        {
            $digit    = random_int( 1 , 100 );
            $faker    = Faker ::create();
            $postData = [
                'name'  => $faker -> firstName() ,
                'email' => "norris07@example$digit.org" ,
                'id'    => 1 ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testEmailMissingInContactUpdate ()
        {
            $faker    = Faker ::create();
            $postData = [
                'name'  => $faker -> firstName() ,
                'id'    => 1 ,
                'phone' => '+2567' . random_int( 10000000 , 99999999 ) ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testIdMissingInContactUpdate ()
        {
            $faker    = Faker ::create();
            $postData = [
                'name'  => $faker -> firstName() ,
                'email' => $faker -> safeEmail() ,
                'phone' => '+2567' . random_int( 11111111 , 99999999 ) ,
            ];
            $response = $this -> json( 'POST' , '/api/contacts' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
