<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class ExpenseCategoryTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testAllExpenseCategoriesEndPoint ()
        {
            $response = $this -> get( '/api/expense-categories' );
            $response -> assertStatus( 200 );
        }

        public function testAllExpenseCategories ()
        {
            $response = $this -> json( 'GET' , '/api/expense-categories?user_id=64' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              [
                                  'id' ,
                                  'name'
                              ]
                          ]
                      ] );
        }
    }
