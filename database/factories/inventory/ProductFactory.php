<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class ProductFactory extends Factory
    {
        public function definition () : array
        {
            return [
                'name'             => $this -> faker -> word() ,
                'user_id'          => 427 ,
                'category'         => $this -> faker -> numberBetween( 1 , 10 ) ,
                'sub_category'     => $this -> faker -> numberBetween( 1 , 10 ) ,
                'code'             => $this -> faker -> postcode() ,
                'photo'            => $this -> faker -> imageUrl() ,
                'units'            => $this -> faker -> numberBetween( 1 , 10 ) ,
                'supplier'         => $this -> faker -> numberBetween( 1 , 10 ) ,
                'retail_price'     => $this -> faker -> numberBetween( 100 , 1000 ) ,
                'whole_sale_price' => $this -> faker -> numberBetween( 100 , 1000 ) ,
                'purchase_price'   => $this -> faker -> numberBetween( 100 , 1000 ) ,
                'balance'          => $this -> faker -> numberBetween( 100 , 1000 ) ,
                'quantity'         => $this -> faker -> numberBetween( 1 , 10 ) ,
                'discount'         => $this -> faker -> numberBetween( 2 , 50 ) ,
                'created_at'       => $this -> faker -> dateTimeBetween( '2020-01-01 00:00:00' , '2023-12-31 23:59:59' ) ,
            ];
        }
    }

