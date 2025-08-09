<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $products = Product::active()->get();

        return $this->successResponse(ProductResource::collection($products));
    }
}
