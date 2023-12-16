<?php

    namespace App\Http\Requests;

    use App\Rules\Phone;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class StoreContactRequest extends FormRequest
    {
        public    $validator          = null;
        protected $stopOnFirstFailure = true;

        public function authorize () : bool
        {
            return true;
        }

        public function rules () : array
        {
            return [
                'name'    => 'required|string' ,
                'phone'   => [
                    'required' , new Phone ,
                    Rule ::unique( 'inv_contacts' ) -> where( function ( $query ) {
                        return $query -> where( 'user_id' , $this -> user_id );
                    } ) ,
                ] ,
                'user_id' => 'required|int|exists:users,id' ,
                'email'   => 'sometimes|required_if:email,!=,|email|unique:inv_contacts,email'
            ];
        }

        public function messages () : array
        {
            return [
                'name.required'    => 'Contact name is required' ,
                'name.string'      => 'Contact name should be a string' ,
                'phone.required'   => 'Phone is required' ,
                'user_id.required' => 'user_id not found in request' ,
                'user_id.int'      => 'user_id should be an integer' ,
                'user_id.exists'   => 'user with given ID not found'
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
