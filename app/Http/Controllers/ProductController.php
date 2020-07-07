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
use App\Util\Pattern;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->header('paginate') ?? 5;
        $products = Product::paginate($paginate);
        Log::info("User requested all products with paginate".$paginate);
        return response()->json($products);
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
        Log::info("User stored a new product with ID".$product->id);
        return response()->json([
            'message' => 'Successfully registered',
            'id' => $product->id], 201);
    }


    public function show(Request $request)
    {
        if(Pattern::verifyValidUUID($request->header('x-user-id'))){
            $product = Product::find($request->header('x-user-id'));
            Log::info("User resquested a product with ID".$product->id);
            return response()->json($product);
        }else{
            Log::info("User requested a product with invalid UUID, bad request");
            return response()->json(['message' => 'UUID not valid'], 400);
        }
    }

    public function update(UpdateProductRequest $request)
    {
        $product = Product::find($request->validated()['id']);
        if(!isset($product)){
            Log::info("User requested product UUID".$request->validated()['id']." doenst exist");
            return response()->json(['message' => 'Passed product doenst exist, any resource was updated'], 400);
        }
        $product->update($request->validated());
        Log::info("User updated product with UUID".$product->id);
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
        if(Pattern::verifyValidUUID($request->header('x-user-id'))){
            $product = Product::find($request->header('x-product-id'));
            if(!isset($product)){
                Log::info("User requested product UUID".$request->validated()['id']." doenst exist");
                return response()->json(['message' => 'Passed product doenst exist, any resource was deleted'], 400);
            }
            $product->delete();
            Log::info("User deleted product with UUID".$product->id);
            return response()->json(['message' => 'Successfully deleted'], 204);
        }else{
            Log::info("User requested a product with invalid UUID, bad request");
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
            Log::info("User requested products with filters paginate".$paginate);
            return response()->json($products);
        }catch (InvalidFilterQuery $exception) {
            Log::info("User requested products with filters not valid, bad request");
            return response()->json([
                'message' => 'Requested filter are not allowed',
                'error_filter' => $exception->unknownFilters,
                'available_filter'=> $exception->allowedFilters
            ], 400);
        }catch (InvalidSortQuery $exception) {
            Log::info("User requested products with sort not valid, bad request");
            return response()->json([
                'message' => 'Requested sort are not allowed',
                'error_sort'=> $exception->unknownSorts,
                'available_sort'=> $exception->allowedSorts
            ], 400);
        }

    }
}
