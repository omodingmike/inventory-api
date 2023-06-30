<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string',
            'category_id'     => 'required|int',
            'code'            => 'required|string',
            'stock'           => 'required|int',
            'unit_id'         => 'required|int',
            'sale_price'      => 'required|int',
            'discount'        => 'required|numeric',
            'wholesale_price' => 'required|int',
            'other_price'     => 'required|int',
            'supplier_id'     => 'required|int',
            'photo'           => 'required|image',
        ]);
        return Product::create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $product = Product::find($id);
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
