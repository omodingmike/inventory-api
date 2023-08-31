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
            $response = $this -> get( '/api/expenses-incomes' );
            $response -> assertStatus( 200 );
        }
    }
