<?php

    namespace Tests\Feature;

    use Tests\TestCase;

    class SaleTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testAllSalesReturnsSuccess ()
        {
            $response = $this -> get( 'api/sales?from=01-08-2021&to=31-08-2023&user_id=1' );
            $response -> assertStatus( 200 );
        }

        public function testAllSalesReturnsData ()
        {
            $response = $this -> json( 'GET' , 'api/sales?from=01-08-2021&to=31-08-2023&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'products_sold' ,
                              'sales' => [
                                  '*' => [
                                      'id' ,
                                      'sale_id' ,
                                      'payment_mode' ,
                                      'grand_total' ,
                                      'created_at' ,
                                  ] ,
                              ] ,
                          ] ,
                      ] );
        }

        public function testFromDateMissingInGetSales ()
        {
            $response = $this -> json( 'GET' , 'api/sales?to=31-08-2023&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testToDateMissingInGetSales ()
        {
            $response = $this -> json( 'GET' , 'api/sales?from=31-08-2023&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInGetSales ()
        {
            $response = $this -> json( 'GET' , 'api/sales?from=31-08-2023&toto=31-08-2021' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testSaleDetails ()
        {
            $response = $this -> json( 'GET' , '/api/sale?user_id=1&sale_id=S638793' );
            $response -> assertStatus( 200 )
                      -> assertJson( [
                          'status'  => 1 ,
                          'message' => 'success' ,
                      ] )
                      -> assertJsonStructure( [
                          'data' => [
                              'id' ,
                              'sale_id' ,
                              'payment_mode' ,
                              'grand_total' ,
                              'created_at' ,
                              'customer' ,
                              'sale_items' => [
                                  '*' => [
                                      'id' ,
                                      'sale_id' ,
                                      'product_id' ,
                                      'quantity' ,
                                      'total' ,
                                      'product' => [
                                          'id' ,
                                          'name' ,
                                          'user_id' ,
                                          'category' ,
                                          'sub_category' ,
                                          'code' ,
                                          'photo' ,
                                          'quantity' ,
                                          'sold' ,
                                          'units' ,
                                          'supplier' ,
                                          'retail_price' ,
                                          'whole_sale_price' ,
                                          'purchase_price' ,
                                          'balance' ,
                                          'discount' ,
                                          'created_at' ,
                                      ] ,
                                  ] ,
                              ] ,
                          ] ,
                      ] );
        }

        public function testUserIdMissingInSaleDetails ()
        {
            $response = $this -> json( 'GET' , '/api/sale?sale_id=S638793' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testSaleIdMissingInSaleDetails ()
        {
            $response = $this -> json( 'GET' , '/api/sale?user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testStoreSale ()
        {
            $postData = [
                'contact_id'   => 1 ,
                'grand_total'  => 34000 ,
                'user_id'      => 50 ,
                'payment_mode' => 'cash' ,
                'items'        => [
                    [
                        'name'     => 'odit' ,
                        'amount'   => 1000 ,
                        'quantity' => 10 ,
                        'total'    => 2000
                    ]
                ]
            ];
            $response = $this -> json( 'POST' , '/api/sales' , $postData );
            $response -> assertStatus( 201 )
                      -> assertJson( [
                          'status'  => 1 ,
                          'message' => 'success' ,
                      ] )
                      -> assertJsonStructure( [
                          'data' => [
                              'grand_total' ,
                              'payment_mode' ,
                              'sale_id' ,
                              'created_at' ,
                              'id' ,
                          ] ,
                      ] );
        }

        public function testContactIdMissingInSaleStore ()
        {
            $postData = [
                'grand_total'  => 34000 ,
                'user_id'      => 50 ,
                'payment_mode' => 'cash' ,
                'items'        => [
                    [
                        'name'     => 'odit' ,
                        'amount'   => 1000 ,
                        'quantity' => 10 ,
                        'total'    => 2000
                    ]
                ]
            ];
            $response = $this -> json( 'POST' , '/api/sales' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testGrandTotalMissingInSaleStore ()
        {
            $postData = [
                'contact_id'   => 1 ,
                'user_id'      => 50 ,
                'payment_mode' => 'cash' ,
                'items'        => [
                    [
                        'name'     => 'odit' ,
                        'amount'   => 1000 ,
                        'quantity' => 10 ,
                        'total'    => 2000
                    ]
                ]
            ];
            $response = $this -> json( 'POST' , '/api/sales' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInSaleStore ()
        {
            $postData = [
                'contact_id'   => 1 ,
                'grand_total'  => 34000 ,
                'payment_mode' => 'cash' ,
                'items'        => [
                    [
                        'name'     => 'odit' ,
                        'amount'   => 1000 ,
                        'quantity' => 10 ,
                        'total'    => 2000
                    ]
                ]
            ];
            $response = $this -> json( 'POST' , '/api/sales' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testPaymentModeMissingInSaleStore ()
        {
            $postData = [
                'contact_id'  => 1 ,
                'grand_total' => 34000 ,
                'user_id'     => 50 ,
                'items'       => [
                    [
                        'name'     => 'odit' ,
                        'amount'   => 1000 ,
                        'quantity' => 10 ,
                        'total'    => 2000
                    ]
                ]
            ];
            $response = $this -> json( 'POST' , '/api/sales' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testItemsArrayissingInSaleStore ()
        {
            $postData = [
                'contact_id'   => 1 ,
                'grand_total'  => 34000 ,
                'user_id'      => 50 ,
                'payment_mode' => 'cash' ,
            ];
            $response = $this -> json( 'POST' , '/api/sales' , $postData );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }
    }
