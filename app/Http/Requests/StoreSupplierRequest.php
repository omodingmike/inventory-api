<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreSupplierRequest extends FormRequest
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
                'name'  => 'required|string|unique:inv_suppliers,name' ,
                'photo' => 'sometimes|required|image'
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
