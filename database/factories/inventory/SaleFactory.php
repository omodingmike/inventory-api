<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class SaleFactory extends Factory
    {
        public function definition () : array
        {
            return [
                'sale_id'      => 'S123456' ,
                'grand_total'  => $this -> faker -> numberBetween( 10000 , 1000000 ) ,
                'contact_id'   => 1 ,
                'user_id'      => 427 ,
                'payment_mode' => $this -> faker -> randomElement( [ 'cash' , 'credit' ] ) ,
                'created_at'   => $this -> faker -> dateTimeBetween( '2020-01-01 00:00:00' , '2023-12-31 23:59:59' )
            ];
        }
    }
