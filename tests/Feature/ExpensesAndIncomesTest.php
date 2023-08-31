<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class ExpensesAndIncomesTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testExpensesAndIncomesEndpoint ()
        {
            $response = $this -> get( '/api/expenses-incomes?user_id=1&from=01-08-2021&to=31-08-2023' );
            $response -> assertStatus( 200 );
        }

        public function testUserIdMissingInExpenseCategories ()
        {
            $response = $this -> get( '/api/expenses-incomes?from=01-08-2021&to=31-08-2023' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testFromDateMissingInExpenseCategories ()
        {
            $response = $this -> get( '/api/expenses-incomes?user_id=1&to=31-08-2023' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testToDateMissingInExpenseCategories ()
        {
            $response = $this -> get( '/api/expenses-incomes?user_id=1&from=01-08-2021' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
