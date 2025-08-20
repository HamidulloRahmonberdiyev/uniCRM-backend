<?php

namespace App\Repositories\Neighborhood;

use App\Models\Neighborhood;
use App\Repositories\Interfaces\NeighborhoodRepositoryInterface;

class NeighborhoodRepository implements NeighborhoodRepositoryInterface
{
  public function findById(int $id): ?Neighborhood
  {
    return Neighborhood::find($id);
  }
}
