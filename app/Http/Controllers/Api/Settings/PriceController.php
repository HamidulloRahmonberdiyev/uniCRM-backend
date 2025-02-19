<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Models\Price;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $prices = Price::all();

        return $this->successResponse($prices);
    }

    public function show()
    {
        return response()->json([Price::firstOrFail()]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required|string',
            'quantity' => 'required|integer',
        ]);

        try {
            $price = Price::create($validatedData);
            return $this->successResponse($price, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create source', 500);
        }
    }

    public function update(Request $request, Price $price)
    {
        $validatedData = $request->validate([
            'price' => 'required|string',
            'quantity' => 'required|integer',
        ]);

        try {
            $price->update($validatedData);
            return $this->successResponse($price, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update price', 500);
        }
    }

    public function destroy(Price $price)
    {
        $price->delete();

        return $this->successResponse('price deleted successfully', 200);
    }
}
