<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SorterResource;
use App\Models\Sorter;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class SorterController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $sorters = Sorter::query()->get();

        return SorterResource::collection($sorters);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required|string',
            'number' => 'required|integer',
        ]);

        try {
            $sorter = Sorter::create($validatedData);
            return $this->successResponse(new SorterResource($sorter), 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create sorter', 500);
        }
    }

    public function destroy(Sorter $sorter)
    {
        $sorter->delete();

        return $this->successResponse('Sorter deleted successfully', 200);
    }
}
