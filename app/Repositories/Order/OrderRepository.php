<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
  public function create(array $data): Order
  {
    return Order::create($data);
  }

  public function findById(int $id): ?Order
  {
    return Order::find($id);
  }

  public function update(Order $order, array $data): Order
  {
    $order->update($data);
    return $order->refresh();
  }

  public function delete(Order $order): bool
  {
    return $order->delete();
  }

  public function getByCustomer(int $customerId): Collection
  {
    return Order::where('customer_id', $customerId)->get();
  }
}
