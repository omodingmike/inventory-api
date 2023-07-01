<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
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
        return Product::all();
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
        $uploaded_image = $request->file('photo');
        $filename = 'public/images/' . time() . '.' . $uploaded_image->getClientOriginalExtension();
        // Create an instance of the Intervention Image
        $image = Image::make($uploaded_image);
        // Resize the image if needed
        $image->resize(100, 100);
        Storage::put($filename, $image->encode());
        $validated['photo'] = url('/') . Storage::url($filename);
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

    public function filterProducts(Request $request)
    {
        // Have to  add a when clause
        $startDate = Carbon::parse($request->query('from'))->startOfDay();
        $endDate = Carbon::parse($request->query('to'))->endOfDay();
        return Product::where('category_id', $request->query('category_id'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
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
