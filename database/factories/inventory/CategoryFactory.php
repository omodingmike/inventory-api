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
                'name'        => $this -> faker -> name() ,
                'photo'       => $this -> faker -> imageUrl() ,
                'description' => '10 pcs - 8 types - 3 sizes' ,
            ];
        }
    }
