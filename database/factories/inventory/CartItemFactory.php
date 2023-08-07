<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class CartItemFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'sale_id'   => $this -> faker -> numberBetween( 1 , 1000 ) ,
                'productID' => $this -> faker -> numberBetween( 1 , 1000 ) ,
                'quantity'  => $this -> faker -> numberBetween( 1 , 100 ) ,
                'total'     => $this -> faker -> numberBetween( 10000 , 100000 ) ,
            ];
        }
    }

