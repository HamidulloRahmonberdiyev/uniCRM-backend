<?php

namespace App\Services\Location;

use App\Models\Neighborhood;

class NeighborhoodService
{
    public function getNeighborhoods(array $data)
    {
        return Neighborhood::when(isset($data['district_id']), fn($query) => $query->where('district_id', $data['district_id']))
            ->when(isset($data['status']), fn($query) => $query->where('status', $data['status']))
            ->when(isset($data['name']), fn($query) => $query->where(function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['name']}%")
                    ->orWhere('second_name', 'like', "%{$data['name']}%");
            }))
            ->select(['id', 'name', 'district_id', 'status'])
            ->orderBy('id')
            ->paginate(20);
    }

    public function createNeighborhood(array $data)
    {
        $model = Neighborhood::create([
            'district_id' => $data['district_id'],
            'name' => $data['name'],
            'second_name' => $data['second_name'] ?? null,
            'status' => $data['status'],
        ]);

        return $model;
    }

    public function updateNeighborhood(array $data, $model)
    {
        $model->update([
            'district_id' => $data['district_id'] ?? $model->district_id,
            'name' => $data['name'] ?? $model->name,
            'second_name' => $data['second_name'] ?? $model->second_name,
            'status' => $data['status'] ?? $model->status,
        ]);

        return $model;
    }
}
