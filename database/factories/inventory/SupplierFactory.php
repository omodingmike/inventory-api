<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class SupplierFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'name'  => $this -> faker -> firstName() ,
                'photo' => $this -> faker -> imageUrl() ,

            ];
        }
    }
