<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {

        $variants = DB::table('product_variants as pv')
            ->join('variants as v', 'pv.variant_id', '=', 'v.id')
            ->groupBy('pv.variant', 'v.title')
            ->orderBy('pv.variant_id')
            ->select('pv.variant', 'v.title')
            ->get();
        $groupedVariants = $variants->groupBy('title');

        $results = DB::table('products')
            ->leftJoin('product_variant_prices', 'products.id', '=', 'product_variant_prices.product_id')
            ->leftJoin('product_variants AS pv1', 'product_variant_prices.product_variant_one', '=', 'pv1.id')
            ->leftJoin('product_variants AS pv2', 'product_variant_prices.product_variant_two', '=', 'pv2.id')
            ->leftJoin('product_variants AS pv3', 'product_variant_prices.product_variant_three', '=', 'pv3.id')
            ->select('products.title', 'products.created_at', 'products.description', 'pv1.variant AS product_variant_one', 'pv2.variant AS product_variant_two', 'pv3.variant AS product_variant_three', 'product_variant_prices.price', 'product_variant_prices.stock')
            ->get()
            ->groupBy('title');

        $products = $results->map(function ($group) {
            return [
                'title' => $group->first()->title,
                'created' => Carbon::parse($group->first()->created_at)->format('d-M-Y'),
                'description' => $group->first()->description,
                'variants' => $group->map(function ($item) {
                    return [
                        'product_variant_one' => $item->product_variant_one,
                        'product_variant_two' => $item->product_variant_two,
                        'product_variant_three' => $item->product_variant_three,
                        'price' => $item->price,
                        'stock' => $item->stock,
                    ];
                }),
            ];
        })->toArray();
        $count = count($products);
        $products = $this->paginate($products, 3);
        $products->withPath('/product');
        return view('products.index', compact('products', 'count', 'groupedVariants'));
    }

    public function paginate($items, $perPage = 4, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $total = count($items);
        $currentpage = $page;
        $offset = ($currentpage * $perPage) - $perPage;
        $itemstoshow = array_slice($items, $offset, $perPage);
        return new LengthAwarePaginator($itemstoshow, $total, $perPage);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}