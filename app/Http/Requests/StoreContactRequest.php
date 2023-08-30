<?php

    namespace App\Http\Requests;

    use App\Rules\Phone;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreContactRequest extends FormRequest
    {
        public    $validator          = null;
        protected $stopOnFirstFailure = true;

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize () : bool
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules () : array
        {
            return [
                'name'    => 'required|string' ,
                'phone'   => [ 'required' , new Phone , 'unique:inv_contacts,phone' ] ,
                'user_id' => 'required|string|exists:users,id' ,
                'email'   => 'sometimes|email|unique:inv_contacts,email' ,
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
