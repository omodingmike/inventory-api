<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class UpdateProductRequest extends FormRequest
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
                'name'             => 'required|string' ,
                'category'         => 'required|string' ,
                'sub_category'     => 'required|string' ,
                'code'             => 'required|string' ,
                'quantity'         => 'required|int' ,
                'units'            => 'required|string' ,
                'retail_price'     => 'required|int' ,
                'discount'         => 'required|int' ,
                'whole_sale_price' => 'required|int' ,
                'purchase_price'   => 'required|int' ,
                'supplier'         => 'required|string' ,
//                'photo'            => 'required|image' ,
//                'user_id'          => 'required|int|exists:users,id' ,
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
