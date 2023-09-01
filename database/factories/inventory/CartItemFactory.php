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
                'sale_id'    => 1 ,
                'product_id' => 1 ,
                'quantity'   => $this -> faker -> numberBetween( 1 , 100 ) ,
                'total'      => $this -> faker -> numberBetween( 10000 , 100000 ) ,
            ];
        }
    }

