<?php

    namespace App\Traits;

    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Validator;

    trait DateTrait
    {
        public function dateRange ( Request $request ) : array
        {
            $start_date = $this -> startDate( $request );
            $end_date   = $this -> endDate( $request );
            return [ $start_date , $end_date ];
        }

        public function startDate ( Request $request )
        {
            return Carbon ::createFromFormat( 'd-m-Y' , $this -> from( $request ) ) -> copy() -> startOfDay();
        }

        public function from ( Request $request )
        {
            return $request -> query( 'from' );
        }

        public function endDate ( Request $request )
        {
            return Carbon ::createFromFormat( 'd-m-Y' , $this -> to( $request ) ) -> copy() -> endOfDay();
        }

        public function to ( Request $request )
        {
            return $request -> query( 'to' );
        }

        public function daysInMonth ( Request $request ) : int
        {
            return $this -> endDate( $request ) -> daysInMonth;
        }

        public function validateDate ( Request $request )
        {
            $validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'sometimes|bail|required|date' ,
                    'to'   => 'sometimes|bail|required|date' ,
                ]
            );
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                return $validator -> messages() -> first();
            }
            return null;
        }
    }