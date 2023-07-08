<?php

    namespace App\Http\Controllers;

    use App\Models\Expense;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use LaravelIdea\Helper\App\Models\_IH_Expense_C;

    class ExpenseController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return Expense[]|Collection|Response|_IH_Expense_C
         */
        public function index ()
        {
            return Expense ::all();
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         * @return Response
         */
        public function store (Request $request)
        {
            $validated = $request -> validate( [ 'name' => 'required', 'amount' => 'required', 'date' => 'required' ] );
            $validated[ 'date' ] = date( 'Y-m-d', strtotime( $request -> date ) );
            return Expense ::create( $validated );
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
