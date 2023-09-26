<?php

    namespace App\Traits;

    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Validator;

    trait DateTrait
    {
        public function validateDate ( Request $request )
        {
            $validator = Validator ::make( $request -> all() ,
                [
                    'from' => 'bail|required|date' ,
                    'to'   => 'bail|required|date' ,
                ]
            );
            if ( $validator -> stopOnFirstFailure() -> fails() ) {
                return $validator -> messages() -> first();
            }
            return null;
        }

        public function dateRange ( Request $request ) : array
        {
            $start_date = $this -> startDate( $request );
            $end_date   = $this -> endDate( $request );
            return [ $start_date , $end_date ];
        }

        public function startDate ( Request $request )
        {
            return Carbon ::createFromFormat( 'd-m-Y' , $request -> query( 'from' ) ) -> copy() -> startOfDay();
        }

        public function endDate ( Request $request )
        {
            return Carbon ::createFromFormat( 'd-m-Y' , $request -> query( 'to' ) ) -> copy() -> endOfDay();
        }

        public function daysInMonth ( Request $request ) : int
        {
            return $this -> endDate( $request ) -> daysInMonth;
        }
    }