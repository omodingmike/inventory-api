<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelIdea\Helper\App\Models\_IH_Unit_C;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Unit[]|Collection|Response|_IH_Unit_C
     */
    public function index()
    {
        return Unit::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return Unit::create($request->validate(['name' => 'required|string', 'symbol' => 'required|string']));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
