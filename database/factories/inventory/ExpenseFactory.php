<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class ExpenseFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'amount'     => $this -> faker -> numberBetween( 10000 , 500000 ) ,
                'user_id'    => 427 ,
                'expense_id' => 1 ,
                'date'       => $this -> faker -> dateTimeBetween( '2020-01-01 00:00:00' , '2023-12-31 23:59:59' ) ,
            ];
        }
    }
