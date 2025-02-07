<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    use ApiJsonResponceTrait;

    public function index()
    {
        $sources = Source::all();

        return $this->successResponse($sources);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        try {
            $source = Source::create($validatedData);
            return $this->successResponse($source, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create source', 500);
        }
    }

    public function update(Request $request, Source $source)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        try {
            $source->update($validatedData);
            return $this->successResponse($source, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update source', 500);
        }
    }

    public function destroy(Source $source)
    {
        $source->delete();

        return $this->successResponse('source deleted successfully', 200);
    }
}
