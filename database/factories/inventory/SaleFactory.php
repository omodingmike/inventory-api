<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class SaleFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'sale_id'     => 'S' . $this -> faker -> numberBetween( 100000 , 999999 ) ,
                'grand_total' => $this -> faker -> numberBetween( 10000 , 1000000 ) ,
                'contact_id'  => $this -> faker -> numberBetween( 1 , 50 ) ,
                'user_id'     => $this -> faker -> numberBetween( 1 , 10 ) ,
                'mode'        => $this -> faker -> randomElement( [ 'cash' , 'credit' ] ) ,
                'created_at'  => $this -> faker -> dateTimeBetween( '01-08-2023' , '01-08-2023' )
            ];
        }
    }
