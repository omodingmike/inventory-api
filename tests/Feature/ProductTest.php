<?php

    namespace Tests\Feature;

    use Illuminate\Http\UploadedFile;
    use Tests\TestCase;

    class ProductTest extends TestCase
    {
        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function testFilterCategoryProducts ()
        {
            $response = $this -> json( 'get' , '/api/filter-category-products?category=1&from=01-02-2021&to=01-09-2023&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'total_quantity' ,
                              'total_balance' ,
                              'products' => [
                                  [
                                      'name' ,
                                      'user_id' ,
                                      'category'     => [
                                          'id' ,
                                          'name' ,
                                          'description' ,
                                          'photo' ,
                                      ] ,
                                      'sub_category' => [
                                          'id' ,
                                          'name' ,
                                      ] ,
                                      'code' ,
                                      'photo' ,
                                      'quantity' ,
                                      'sold' ,
                                      'units'        => [
                                          'id' ,
                                          'name' ,
                                          'symbol' ,
                                      ] ,
                                      'supplier'     => [
                                          'id' ,
                                          'name' ,
                                          'photo' ,
                                      ] ,
                                      'retail_price' ,
                                      'whole_sale_price' ,
                                      'purchase_price' ,
                                      'balance' ,
                                      'discount' ,
                                      'created_at' ,
                                  ] ,
                              ] ,
                          ] ,
                      ] );
        }

        public function testProductDetails ()
        {
            $expectedData = [
                'status' ,
                'message' ,
                'data' => [
                    'id' ,
                    'name' ,
                    'user_id' ,
                    'category'     => [
                        'id' ,
                        'name' ,
                        'description' ,
                        'photo' ,
                    ] ,
                    'sub_category' => [
                        'id' ,
                        'name' ,
                    ] ,
                    'code' ,
                    'photo' ,
                    'quantity' ,
                    'sold' ,
                    'units'        => [
                        'id' ,
                        'name' ,
                        'symbol' ,
                    ] ,
                    'supplier'     => [
                        'id' ,
                        'name' ,
                        'photo' ,
                    ] ,
                    'retail_price' ,
                    'whole_sale_price' ,
                    'purchase_price' ,
                    'balance' ,
                    'discount' ,
                    'created_at' ,
                ] ,
            ];

            $response = $this -> get( '/api/product-details?id=1&user_id=36' ); // Replace with the actual endpoint URL

            $response -> assertStatus( 200 )
                      -> assertJsonStructure( $expectedData );
        }

        public function testProductStore ()
        {
            $product_name = 'product' . time();
            $data         = [
                'name'             => $product_name ,
                'user_id'          => 1 ,
                'category'         => 'Brady' ,
                'sub_category'     => 'aut' ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'units'            => 'et' ,
                'supplier'         => 'Velda' ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'balance'          => 75000 ,
                'discount'         => 10 ,
            ];

            $response = $this -> post( '/api/products' , $data );

            $response -> assertStatus( 201 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' => [
                              'name' ,
                              'category' ,
                              'sub_category' ,
                              'code' ,
                              'quantity' ,
                              'units' ,
                              'retail_price' ,
                              'discount' ,
                              'whole_sale_price' ,
                              'purchase_price' ,
                              'supplier' ,
                              'photo' ,
                              'user_id' ,
                              'balance' ,
                              'created_at' ,
                              'id' ,
                          ]
                      ] );

            // Optionally, you can also assert that the record was inserted into the database
            $this -> assertDatabaseHas( 'inv_products' , [
                'name' => $product_name
            ] );
        }

        public function testProductUpdate ()
        {
            $product_name = 'product' . time();
            $data         = [
                'name'             => $product_name ,
                'user_id'          => 1 ,
                'category'         => 'Brady' ,
                'sub_category'     => 'aut' ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'units'            => 'et' ,
                'supplier'         => 'Velda' ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'balance'          => 75000 ,
                'discount'         => 10 ,
                'id'               => 1
            ];

            $response = $this -> post( '/api/update-product' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data'
                      ] );

            // Optionally, you can also assert that the record was inserted into the database
            $this -> assertDatabaseHas( 'inv_products' , [
                'name' => $product_name
            ] );
        }

    }
