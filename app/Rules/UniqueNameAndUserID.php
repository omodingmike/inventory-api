<?php

    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;
    use Illuminate\Support\Facades\DB;

    class UniqueNameAndUserID implements Rule
    {
        private int    $user_id;
        private string $table;

        /**
         * Create a new rule instance.
         *
         * @return void
         */
        public function __construct ( int $user_id , string $table )
        {
            $this -> user_id = $user_id;
            $this -> table   = $table;
        }

        /**
         * Determine if the validation rule passes.
         *
         * @param string $attribute
         * @param mixed  $value
         * @return bool
         */
        public function passes ( $attribute , $value ) : bool
        {
            return !DB ::table( $this -> table )
                       -> where( 'user_id' , $this -> user_id )
                       -> where( 'name' , $value )
                       -> exists();
        }

        /**
         * Get the validation error message.
         *
         * @return string
         */
        public function message () : string
        {
            return 'Name already taken.';
        }
    }
