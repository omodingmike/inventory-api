<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Expense;
    use App\Models\inventory\Sale;
    use App\Models\inventory\User;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;


    class ExpenseController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @param Request $request
         * @return array
         */
        public function index ( Request $request )
        {
            $user_id   = $request -> user_id;
            $startDate = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
            $endDate   = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();

            $revenue = Sale ::where( 'user_id' , $user_id )
                            -> whereBetween( 'created_at' , [ $startDate , $endDate ] )
                            -> get();
            $user    = User ::find( $request -> user_id );
            if ( $user ) {
                return [
                    'status' => 1 ,
                    'data'   => $user -> expenses ];
            } else {
                return [
                    'status'  => 'failed' ,
                    'message' => 'No expenses found'
                ];
            }
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store ( Request $request )
        {
            $validated           = $request -> validate(
                [ 'name'    => 'required' ,
                  'amount'  => 'required' ,
                  'date'    => 'required' ,
                  'user_id' => 'required'
                ] );
            $validated[ 'date' ] = date( 'Y-m-d' , strtotime( $request -> date ) );
            $expense             = Expense ::create( $validated );

            if ( $expense ) {
                return [
                    'status'  => 'ok' ,
                    'message' => 'success'
                ];
            } else {
                return [
                    'status'  => 'failed' ,
                    'message' => 'Operation failed'
                ];
            }
        }

        public function expensesAndIncomes ( Request $request )
        {
            $user_id         = $request -> user_id;
            $startDate       = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
            $endDate         = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();
            $expense_columns = [ 'name' , 'amount' , 'created_at' ];

            try {
                $data = Expense ::select( $expense_columns )
                                -> where( 'user_id' , $user_id )
                                -> whereBetween( 'created_at' , [ $startDate , $endDate ] )
                                -> union( Sale ::select( [ 'sale_id' , 'grand_total' , 'created_at' ] )
                                               -> where( 'user_id' , $user_id )
                                               -> whereBetween( 'created_at' , [ $startDate , $endDate ] )
                                )
                                -> orderBy( 'created_at' )
                                -> get();
                return [
                    'status' => 1 ,
                    'data'   => $data
                ];
            }
            catch ( Exception $exception ) {
                return [
                    'status'  => 'failed' ,
                    'message' => $exception -> getMessage()
                ];
            }
        }
    }
