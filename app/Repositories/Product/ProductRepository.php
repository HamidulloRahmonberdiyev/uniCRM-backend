<?php

namespace App\Repositories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
  public function getAll(): Collection
  {
    return Product::active()->get();
  }

  public function getPaginated(): LengthAwarePaginator
  {
    return Product::active()->paginate(20);
  }

  public function findById(int $id): ?Product
  {
    return Product::find($id);
  }

  public function create(array $data): Product
  {
    return Product::create($data);
  }

  public function update(Product $product, array $data): bool
  {
    return $product->update($data);
  }

  public function delete(Product $product): bool
  {
    return $product->delete();
  }

  public function getAllWithTrashed(): Collection
  {
    return Product::withTrashed()->get();
  }
}
