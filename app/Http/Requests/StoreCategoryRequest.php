<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreCategoryRequest extends FormRequest
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
                'photo'       => 'bail|sometimes|required|image' ,
                'user_id'     => 'bail|sometimes|required|int|exists:users,id' ,
                'name'        => [ 'bail' , 'required' , 'string' , 'unique:inv_categories' ] ,
                'description' => 'bail|required|string'
            ];
        }

        public function messages () : array
        {
            return [
                'name.required'        => 'Category name is required' ,
                'name.string'          => 'Category name should be a string' ,
                'photo.required'       => 'Category photo is required' ,
                'photo.image'          => 'Category photo should be an image' ,
                'description.required' => 'Description is required' ,
                'description.string'   => 'Description is should be a string' ,
                'user_id.required'     => 'user_id not found in request' ,
                'user_id.int'          => 'user_id should be an integer' ,
                'user_id.exists'       => 'user with given ID not found'
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }

    }
