<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->header('paginate') ?? 5;
        $products = Product::paginate($paginate);
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json([
            'message' => 'Successfully registered',
            'id' => $product->id], 201);
    }


    public function show(Request $request)
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        if(preg_match($UUIDv4, $request->header('x-user-id'))){
            $product = Product::find($request->header('x-user-id'));
            return response()->json($product);
        }else{
            return response()->json(['message' => 'UUID not valid'], 400);
        }
    }

    public function update(UpdateProductRequest $request)
    {
        $product = Product::find($request->validated()['id']);
        if(!isset($product)){
            return response()->json(['message' => 'Passed product doenst exist, any resource was updated'], 400);
        }
        $product->update($request->validated());
        return response()->json([
            'message' => 'Successfully updated',
            'id' => $product->id
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        if(preg_match($UUIDv4, $request->header('x-product-id'))){
            $product = Product::find($request->header('x-product-id'));
            if(!isset($product)){
                return response()->json(['message' => 'Passed product doenst exist, any resource was deleted'], 400);
            }
            $product->delete();
            return response()->json(['message' => 'Successfully deleted'], 204);
        }else{
            return response()->json(['message' => 'UUID not valid'], 400);
        }
    }

    public function list(Request $request){
        try {
            $paginate = $request->header('paginate') ?? 5;
            $products = QueryBuilder::for(Product::class)->allowedFilters([
                AllowedFilter::exact('name'),
                AllowedFilter::exact('description'),
                AllowedFilter::exact('categorie'),
                AllowedFilter::exact('price'),
                AllowedFilter::exact('stock_quantity'),
                AllowedFilter::scope('min_price'),
                AllowedFilter::scope('max_price'),
            ])->allowedSorts('name','description','categorie','price','stock_quantity')
            ->paginate($paginate);
            return response()->json($products);
        }catch (InvalidFilterQuery $exception) {
            return response()->json([
                'message' => 'Requested filter are not allowed',
                'error_filter' => $exception->unknownFilters,
                'available_filter'=> $exception->allowedFilters
            ], 400);
        }catch (InvalidSortQuery $exception) {
            return response()->json([
                'message' => 'Requested sort are not allowed',
                'error_sort'=> $exception->unknownSorts,
                'available_sort'=> $exception->allowedSorts
            ], 400);
        }

    }
}
