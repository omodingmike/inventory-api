<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreSubCategoryRequest extends FormRequest
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
                'name'        => 'required|string|unique:inv_sub_categories' ,
                'category_id' => 'required|int|exists:inv_categories,id' ,
//                'user_id'     => 'required|int|exists:inv_users,id'
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
