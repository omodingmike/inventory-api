<?php

    namespace App\Http\Requests;

    use App\Rules\Phone;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class UpdateContactRequest extends FormRequest
    {
        public    $validator          = null;
        protected $stopOnFirstFailure = true;

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize ()
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
                'name'  => 'required|string' ,
                'phone' => [ 'required' , new Phone , 'unique:inv_contacts,phone' ] ,
                'id'    => 'required|int|exists:inv_contacts,id' ,
                'email' => 'sometimes|email|unique:inv_contacts,email' ,
            ];
        }
        
        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
