<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;

    class StoreProductRequest extends FormRequest
    {
        public $validator = null;
        /**
         * @var mixed
         */
        public $quantity;
        /**
         * @var mixed
         */
        public    $retail_price;
        protected $stopOnFirstFailure = true;

        public function authorize () : bool
        {
            return true;
        }

        public function rules () : array
        {
            return [
//                'name'             => [ 'required' , 'string' , new UniqueNameAndUserID( $this -> input( 'user_id' ) , 'inv_products' ) ] ,
                'name'           => [ 'required' , 'string' ] ,
                'category'       => 'required|exists:inv_categories,name' ,
                'sub_category'   => 'required|exists:inv_sub_categories,name' ,
                'code'           => 'required|string' ,
                'quantity'       => 'required|int' ,
                'units'          => 'required|exists:inv_units,name' ,
                'retail_price'   => 'required|int' ,
                'purchase_price' => 'required|int' ,
                'supplier'       => 'required|exists:inv_suppliers,name' ,
                'photo'          => 'required|image' ,
                'user_id'        => 'required|int|exists:users,id' ,
            ];
        }

        public function failedValidation ( Validator $validator )
        {
            $this -> validator = $validator;
        }
    }
