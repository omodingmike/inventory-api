<?php

namespace App\Http\Controllers;

use App\helpers\Uploads;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelIdea\Helper\App\Models\_IH_Supplier_C;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Supplier[]|Collection|Response|_IH_Supplier_C
     */
    public function index()
    {
        return Supplier::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validated          = $request->validate(['name' => 'required|string', 'photo' => 'required|image',]);
        $validated['photo'] = Uploads::upload_image($request);
        return Supplier::create($validated);
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
     * @param int     $id
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
