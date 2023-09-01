<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class ExpenseTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testExpenseEndPoint ()
        {
            $response = $this -> get( '/api/expenses' );

            $response -> assertStatus( 200 );
        }

        public function testAllUserExpenses ()
        {
            $response = $this -> json( 'GET' , '/api/expenses?user_id=1&from=01-01-2021&to=01-12-2023' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'expense_percentage' ,
                              'income_percentage' ,
                              'expenses' => [
                                  '*' => [
                                      'id' ,
                                      'name' ,
                                      'amount' ,
                                      'date'
                                  ]
                              ]
                          ]
                      ] );
        }

        public function testUserIdMissingInAllUserExpenses ()
        {
            $response = $this -> json( 'GET' , '/api/expenses?from=01-08-2021&to=31-08-2021' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testFromDateMissingInAllUserExpenses ()
        {
            $response = $this -> json( 'GET' , '/api/expenses?user_id=1&to=31-08-2021' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testToDateMissingInAllUserExpenses ()
        {
            $response = $this -> json( 'GET' , '/api/expenses?user_id=1&from=01-08-2021' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testStoreExpense ()
        {
            $payload  = [
                'category_id' => 1 ,
                'amount'      => 789 ,
                'date'        => '24-07-2023' ,
                'user_id'     => 1
            ];
            $response = $this -> json( 'POST' , '/api/expenses' , $payload ); // Replace with your actual endpoint
            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'expense_id' ,
                              'amount' ,
                              'date' ,
                              'user_id' ,
                              'id'
                          ]
                      ] );
        }

        public function testCategoryIdMissingInStoreExpense ()
        {
            $payload  = [
                'amount'  => 789 ,
                'date'    => '24-07-2023' ,
                'user_id' => 1
            ];
            $response = $this -> json( 'POST' , '/api/expenses' , $payload ); // Replace with your actual endpoint
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testAmountMissingInStoreExpense ()
        {
            $payload  = [
                'category_id' => 1 ,
                'date'        => '24-07-2023' ,
                'user_id'     => 1
            ];
            $response = $this -> json( 'POST' , '/api/expenses' , $payload ); // Replace with your actual endpoint
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testDateMissingInStoreExpense ()
        {
            $payload  = [
                'category_id' => 1 ,
                'amount'      => 789 ,
                'user_id'     => 1
            ];
            $response = $this -> json( 'POST' , '/api/expenses' , $payload ); // Replace with your actual endpoint
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInStoreExpense ()
        {
            $payload  = [
                'category_id' => 1 ,
                'amount'      => 789 ,
                'date'        => '24-07-2023' ,
            ];
            $response = $this -> json( 'POST' , '/api/expenses' , $payload ); // Replace with your actual endpoint
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
