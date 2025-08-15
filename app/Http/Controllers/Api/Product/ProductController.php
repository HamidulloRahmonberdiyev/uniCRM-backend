<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Paginated\PaginatedResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Traits\ApiJsonResponceTrait;

class ProductController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(
        private ProductService $productService
    ) {}

    public function index()
    {
        $products = $this->productService->getPaginatedProducts();

        return $this->successResponse(
            new PaginatedResource(ProductResource::collection($products), 'products')
        );
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());

        return $this->successResponse(new ProductResource($product), 201);
    }

    public function show(Product $product)
    {
        return $this->successResponse(new ProductResource($product));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->updateProduct($product, $request->validated());

        return $this->successResponse(new ProductResource($product->fresh()));
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);
        return $this->successResponse(null, 204);
    }

    public function list()
    {
        $products = $this->productService->getAllProducts();
        return $this->successResponse(ProductResource::collection($products));
    }
}
