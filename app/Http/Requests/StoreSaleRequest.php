<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreSaleRequest extends FormRequest
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
                'contact_id'   => 'required|int' ,
                'grand_total'  => 'required|int' ,
                'user_id'      => 'required|int|exists:users,id' ,
                'payment_mode' => 'required|string' ,
                'items'        => 'required|array' ,
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
