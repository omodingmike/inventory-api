<?php

    namespace App\Http\Controllers\inventory;

    use App\Models\inventory\Expense;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Carbon;
    use LaravelIdea\Helper\App\Models\_IH_Expense_C;

    class ExpenseController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return _IH_Expense_C|Collection|Expense[]
         */
        public function index ()
        {
            return Expense ::all();
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return string[]
         */
        public function store (Request $request)
        {
            $validated = $request -> validate( [ 'name' => 'required', 'amount' => 'required', 'date' => 'required' ] );
            $validated[ 'date' ] = date( 'Y-m-d', strtotime( $request -> date ) );
            $expense = Expense ::create( $validated );

            if ( $expense ) {
                return [
                    'status'  => 'ok',
                    'message' => 'success'
                ];
            } else {
                return [
                    'status'  => 'failed',
                    'message' => 'Operation failed'
                ];
            }
        }

        public function filterExpenses (Request $request)
        {
            $startDate = Carbon ::parse( $request -> query( 'from' ) ) -> startOfDay();
            $endDate = Carbon ::parse( $request -> query( 'to' ) ) -> endOfDay();

            return Expense ::whereBetween( 'created_at', [ $startDate, $endDate ] )
                           -> get();

//            return DB ::table( 'expenses' )
//                      -> whereBetween( 'created_at', [ $startDate, $endDate ] )
//                      -> get();
        }

        /**
         * Display the specified resource.
         *
         * @param int $id
         * @return Response
         */
        public function show ($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         * @return Response
         */
        public function update (Request $request, $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return Response
         */
        public function destroy ($id)
        {
            //
        }
    }
