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
        public function definition ()
        {
            return [
                'name'   => $this -> faker -> name() ,
                'symbol' => $this -> faker -> name() ,
            ];
        }
    }
