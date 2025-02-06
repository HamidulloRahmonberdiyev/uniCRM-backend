<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Models\Bottle;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class BottleController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $prices = Bottle::all();

        return $this->successResponse($prices);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required|string',
            'quantity' => 'required|integer',
            'date' => 'required|date',
        ]);

        try {
            $bottle = Bottle::create($validatedData);
            return $this->successResponse($bottle, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create bottle', 500);
        }
    }

    public function update(Request $request, Bottle $bottle)
    {
        $validatedData = $request->validate([
            'label' => 'required|string',
            'quantity' => 'required|integer',
            'date' => 'required|date',
        ]);

        try {
            $bottle->update($validatedData);
            return $this->successResponse($bottle, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update bottle', 500);
        }
    }

    public function destroy(Bottle $bottle)
    {
        $bottle->delete();

        return $this->successResponse('bottle deleted successfully', 200);
    }
}
