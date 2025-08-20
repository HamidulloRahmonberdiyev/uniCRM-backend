<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
  public function create(array $data): Order;
  public function findById(int $id): ?Order;
  public function update(Order $order, array $data): Order;
  public function delete(Order $order): bool;
  public function getByCustomer(int $customerId): Collection;
}
