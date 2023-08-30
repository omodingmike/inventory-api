<?php

    namespace App\Http\Requests;

    use App\Rules\UniqueNameAndUserID;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreCategoryRequest extends FormRequest
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
                'name'        => [ 'bail' , 'required' , 'string' , new UniqueNameAndUserID( $this -> input( 'user_id' ) , 'inv_categories' ) ] ,
                'photo'       => 'bail|required|image' ,
                'user_id'     => 'bail|required|string|exists:users,id' ,
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
