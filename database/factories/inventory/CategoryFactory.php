<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class CategoryFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'name'        => $this -> faker -> firstName() ,
                'photo'       => $this -> faker -> imageUrl() ,
                'user_id'     => 1 ,
                'description' => '10 pcs - 8 types - 3 sizes' ,
            ];
        }
    }
