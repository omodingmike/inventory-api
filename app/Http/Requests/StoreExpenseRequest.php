<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreExpenseRequest extends FormRequest
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
                'category_id' => 'required|int|exists:inv_categories,id' ,
                'amount'      => 'required|int' ,
                'date'        => 'required|date' ,
                'user_id'     => 'required|int|exists:users,id'
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
