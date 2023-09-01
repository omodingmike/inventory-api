<?php

    namespace Tests\Feature;

    use App\Models\inventory\Category;
    use App\Models\inventory\SubCategory;
    use App\Models\inventory\Supplier;
    use App\Models\inventory\Unit;
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
            $response = $this -> json( 'get' , '/api/filter-category-products?category=1&from=01-01-2021&to=01-12-2023&user_id=1' );
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

        public function testCategoryMissingInFilterCategoryProducts ()
        {
            $response = $this -> json( 'get' , '/api/filter-category-products?from=01-02-2021&to=01-09-2023&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testFromDateMissingInFilterCategoryProducts ()
        {
            $response = $this -> json( 'get' , '/api/filter-category-products?category=1&to=01-09-2023&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testToDateMissingInFilterCategoryProducts ()
        {
            $response = $this -> json( 'get' , '/api/filter-category-products?category=1&from=01-02-2021&user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInFilterCategoryProducts ()
        {
            $response = $this -> json( 'get' , '/api/filter-category-products?category=1&from=01-02-2021&to=01-09-2023' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
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

            $response = $this -> get( '/api/product-details?id=1&user_id=1' );

            $response -> assertStatus( 200 )
                      -> assertJsonStructure( $expectedData );
        }

        public function testIdMissingInProductDetails ()
        {
            $response = $this -> get( '/api/product-details?user_id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInProductDetails ()
        {
            $response = $this -> get( '/api/product-details?id=1' );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testProductStore ()
        {
            $data = [
                'name'             => 'name' ,
                'user_id'          => 1 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
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
        }

        public function testNameMissingInProductStore ()
        {
            $data     = [
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'balance'          => 75000 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUserIdMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'balance'          => 75000 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testCategoryMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testSubCategoryMissingInProductStore ()
        {
            $data     = [
                'name'    => 'name' . time() ,
                'user_id' => 1 ,

                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,

                'discount' => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testCodeMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testPhotoMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testQuantityMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testUnitsMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testSupplierMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testRetailPriceMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'whole_sale_price' => 800 ,
                'purchase_price'   => 750 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testWholeSalePriceMissingInProductStore ()
        {
            $data     = [
                'name'           => 'name' . time() ,
                'user_id'        => 1 ,
                'code'           => 'ABC123' ,
                'photo'          => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'       => 100 ,
                'category'       => ( Category ::first() ) -> name ,
                'sub_category'   => ( SubCategory ::first() ) -> name ,
                'units'          => ( Unit ::first() ) -> name ,
                'supplier'       => ( Supplier ::first() ) -> name ,
                'retail_price'   => 1000 ,
                'purchase_price' => 750 ,
                'discount'       => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testPurchasePriceMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
                'discount'         => 10 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testDiscountMissingInProductStore ()
        {
            $data     = [
                'name'             => 'name' . time() ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
                'retail_price'     => 1000 ,
                'whole_sale_price' => 800 ,
            ];
            $response = $this -> post( '/api/products' , $data );
            $response -> assertStatus( 200 )
                      -> assertJsonStructure( [
                          'status' ,
                          'message' ,
                          'data' ,
                      ] );
        }

        public function testProductUpdate ()
        {
            $data = [
                'name'             => 'name' ,
                'user_id'          => 1 ,
                'code'             => 'ABC123' ,
                'photo'            => UploadedFile ::fake() -> image( 'product.jpg' ) ,
                'quantity'         => 100 ,
                'category'         => ( Category ::first() ) -> name ,
                'sub_category'     => ( SubCategory ::first() ) -> name ,
                'units'            => ( Unit ::first() ) -> name ,
                'supplier'         => ( Supplier ::first() ) -> name ,
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
        }

    }
