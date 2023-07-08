<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use LaravelIdea\Helper\App\Models\_IH_Category_C;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return _IH_Category_C|Category[]|Collection
     */
    public function index()
    {
//        return Category::with('products.supplier.unit')->get();
        return Category::with('products.supplier', 'products.unit')->get();
    }

    public function filterCategories(Request $request)
    {
        $productName = $request->query('name');
//        return Category::with(['products.supplier', 'products.unit'])
//                       ->where('products', function (Builder $query) use ($productName) {
//                          return $query->where('name', 'like', "%$productName%");
//                       })
//                       ->get();

        return Category::with(['products.supplier', 'products.unit'])
                       ->where('products', function ($query) use ($productName) {
                           return $query->where('name', 'like', "%$productName%");
                       })
                       ->get();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validated      = $request->validate(['name' => 'required|string', 'photo' => 'required|image']);
        $uploaded_image = $request->file('photo');
        $filename       = 'public/images/' . time() . '.' . $uploaded_image->getClientOriginalExtension();
        // Create an instance of the Intervention Image
        $image = Image::make($uploaded_image);
        // Resize the image if needed
        $image->resize(100, 100);
        Storage::put($filename, $image->encode());
        $validated['photo'] = url('/') . Storage::url($filename);
        return Category::create($validated);
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
