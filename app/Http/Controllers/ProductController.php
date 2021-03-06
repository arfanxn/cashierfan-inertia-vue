<?php

namespace App\Http\Controllers;

use App\Actions\CreateOrUpdateProductAction;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Responses\ProductIndexResponse;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            'role_has_permission:products.index|products.create|products.edit|products.delete',
            ['only' => ['index']]
        );
        $this->middleware('role_has_permission:products.create', ['only' => ['create', 'store']]);
        $this->middleware('role_has_permission:products.edit', ['only' => ['edit', 'update']]);
        $this->middleware('role_has_permission:products.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = strtolower($request->get('keyword'));

        $products = Product::query();

        if ($keyword) {
            $products = $products->where(function ($query) use ($keyword) {
                return $query->where(
                    "barcode",
                    "ILIKE",
                    "$keyword%"
                )->orWhere(
                    "name",
                    "ILIKE",
                    "%$keyword%"
                );
            });
        }

        $products = $products->orderBy("updated_at", "DESC")->orderBy("id", "ASC")->simplePaginate(10);

        return ProductIndexResponse::make(compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Product/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        CreateOrUpdateProductAction::exec(
            null,
            $request->barcode,
            $request->name,
            $request->description,
            $request->file("image"),
            $request->tax_percentage,
            $request->profit_percentage,
            $request->gross_price,
            $request->stock
        );

        return redirect()->route("products.index")->with(['message' => "Product created successfully"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // not implemented
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return Inertia::render('Product/Edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        CreateOrUpdateProductAction::exec(
            $product->id,
            $request->barcode,
            $request->name,
            $request->description,
            $request->file("image"),
            $request->tax_percentage,
            $request->profit_percentage,
            $request->gross_price,
            $request->stock
        );

        return redirect()->route("products.index")->with(['message' => "Product updated successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route("products.index")->with(['message' => "Product deleted successfully"]);
    }
}
