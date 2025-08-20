<?php

namespace App\Repositories\Interfaces;

use App\Models\Neighborhood;

interface NeighborhoodRepositoryInterface
{
  public function findById(int $id): ?Neighborhood;
}
