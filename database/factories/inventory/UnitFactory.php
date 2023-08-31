<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class UnitFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition () : array
        {
            return [
                'name'   => $this -> faker -> word() ,
                'symbol' => $this -> faker -> randomElement( [ 'kg' , 'L' , 'pc' ] ) ,
            ];
        }
    }
