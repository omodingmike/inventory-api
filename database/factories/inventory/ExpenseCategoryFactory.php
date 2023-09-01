<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class ExpenseCategoryFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'user_id' => 1 ,
                'name'    => $this -> faker -> word() ,
            ];
        }
    }
