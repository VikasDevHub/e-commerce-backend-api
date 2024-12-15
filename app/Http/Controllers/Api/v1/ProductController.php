<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\v1\Service\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $products = $this->productService->getAllProducts();

            return sendResponse('Product List', $products->toArray());

        }catch (\Exception $e)
        {
            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try
        {
            $validator = Validator::make($request->all(), [
                'product_name' => ['required', 'string', 'min:2', 'max:255'],
                'short_description' => ['required', 'string', 'min:2'],
                'description' => ['required', 'string', 'min:2'],
                'price' => ['required', 'numeric', 'min:0'], // Ensure price is numeric and non-negative
                'thumbnail_image' => ['required', /*'image'*/ 'file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
                'product_image' => ['required', 'array', 'min:1',],
                'product_image.*' => ['file', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240',],
                'product_tags' => ['required', 'array', 'min:1', 'exists:product_labels,id']
            ]);

            if ($validator->fails())
            {
                $error = trans('errors.validation_error');

                return sendError($error['message'], $validator->errors()->toArray(), $error['status_code']);
            }

            $data = $validator->validated();

            $product = $this->productService->addProduct($data);

            if ($product)
            {
                return sendResponse('Product is created successfully', $product->toArray());
            }

            return sendError('unable to save product');

        }
        catch (\Exception $e)
        {
            Log::info($e->getMessage());
            $error = trans('errors.server_error');

            return sendError($error['message'], [], $error['status_code']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
