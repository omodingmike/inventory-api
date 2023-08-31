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
            $response = $this -> json( 'GET' , '/api/expenses?user_id=1&from=01-08-2021&to=31-08-2021' );
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
    }
