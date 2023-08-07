<?php

    namespace Database\Factories\inventory;

    use Illuminate\Database\Eloquent\Factories\Factory;

    class ContactFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array
         */
        public function definition ()
        {
            return [
                'name'    => $this -> faker -> name() ,
                'phone'   => $this -> faker -> phoneNumber() ,
                'email'   => $this -> faker -> safeEmail() ,
                'user_id' => $this -> faker -> numberBetween( 1 , 10 ) ,
            ];
        }
    }
