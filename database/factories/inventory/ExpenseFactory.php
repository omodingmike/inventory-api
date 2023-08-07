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
                'name'    => $this -> faker -> name() ,
                'amount'  => $this -> faker -> numberBetween( 10000 , 50000 ) ,
                'user_id' => $this -> faker -> numberBetween( 1 , 10 ) ,
                'date'    => $this -> faker -> dateTimeBetween( '-3 years' ) ,
            ];
        }
    }
