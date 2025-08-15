<?php

namespace App\Http\Resources\Paginated;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PaginatedResource extends JsonResource
{
    protected string $dataKey;

    public function __construct($resource, string $dataKey = 'data')
    {
        parent::__construct($resource);
        $this->dataKey = $dataKey;
    }

    public function toArray($request)
    {
        return [
            $this->dataKey => $this->collection,
            'meta' => [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
