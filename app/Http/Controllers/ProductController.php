<?php

namespace App\Http\Controllers;

use App\helpers\Uploads;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use LaravelIdea\Helper\App\Models\_IH_Product_C;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return _IH_Product_C|Collection|Product[]
     */
    public function index()
    {
        return Product::with('category', 'supplier', 'unit')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $validated          = $request->validate([
                                                     'name'            => 'required|string',
                                                     'category_id'     => 'required|int',
                                                     'sub_category'    => 'required',
                                                     'code'            => 'required|string|int',
                                                     'stock'           => 'required|int',
                                                     'unit_id'         => 'required|int',
                                                     'sale_price'      => 'required|int',
                                                     'discount'        => 'required|numeric',
                                                     'wholesale_price' => 'required|int',
                                                     'other_price'     => 'required|int',
                                                     'supplier_id'     => 'required|int',
                                                     'photo'           => 'required|image',
                                                 ]);
        $validated['photo'] = Uploads::upload_image($request);
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
    }

    public function filterProducts(Request $request)
    {
        // Have to  add a when clause
        $startDate = Carbon::parse($request->query('from'))->startOfDay();
        $endDate   = Carbon::parse($request->query('to'))->endOfDay();
        return Product::where('category_id', $request->query('category_id'))
                      ->whereBetween('created_at', [$startDate, $endDate])
                      ->get();
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
