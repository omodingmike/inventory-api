<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreExpenseRequest extends FormRequest
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
                'category_id' => 'required|int|exists:inv_expense_categories,id' ,
                'name'        => 'required|string' ,
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
