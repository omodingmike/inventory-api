<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class RevenueTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testAllExpensesReturnsSuccess ()
        {
            $response = $this -> get( '/api/revenues' );
            $response -> assertStatus( 200 );
        }
    }
