<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class CartItemFactory extends Factory
    {
        public function definition () : array
        {
            return [
                'sale_id'    => $this -> faker -> numberBetween( 1 , 1000 ) ,
                'product_id' => 1 ,
                'quantity'   => $this -> faker -> numberBetween( 1 , 100 ) ,
                'total'      => $this -> faker -> numberBetween( 10000 , 10000 ) ,
            ];
        }
    }

