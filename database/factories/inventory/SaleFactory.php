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
                'sale_id'      => 'S123456' ,
                'grand_total'  => $this -> faker -> numberBetween( 10000 , 1000000 ) ,
                'contact_id'   => 1 ,
                'user_id'      => 1 ,
                'payment_mode' => $this -> faker -> randomElement( [ 'cash' , 'credit' ] ) ,
                'created_at'   => $this -> faker -> dateTimeBetween( '01-08-2023' , '01-08-2023' )
            ];
        }
    }
