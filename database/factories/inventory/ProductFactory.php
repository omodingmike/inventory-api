<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class ProductFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'name'           => $this -> faker -> word() ,
                'user_id'        => $this -> faker -> numberBetween( 1 , 10 ) ,
                'category'       => $this -> faker -> numberBetween( 1 , 10 ) ,
                'sub_category'   => $this -> faker -> numberBetween( 1 , 10 ) ,
                'productCode'    => $this -> faker -> postcode() ,
                'photo'          => $this -> faker -> imageUrl() ,
                'units'          => $this -> faker -> numberBetween( 1 , 10 ) ,
                'supplier'       => $this -> faker -> numberBetween( 1 , 10 ) ,
                'retail_price'   => $this -> faker -> numberBetween( 10000 , 100000 ) ,
                'wholeSalePrice' => $this -> faker -> numberBetween( 10000 , 100000 ) ,
                'purchasePrice'  => $this -> faker -> numberBetween( 10000 , 100000 ) ,
                'balance'        => $this -> faker -> numberBetween( 10000 , 100000 ) ,
                'quantity'       => $this -> faker -> numberBetween( 50 , 1000 ) ,
                'discount'       => $this -> faker -> numberBetween( 10 , 60 ) ,
                'created_at'     => $this -> faker -> dateTimeBetween( '-3 years' ) ,
            ];
        }
    }

