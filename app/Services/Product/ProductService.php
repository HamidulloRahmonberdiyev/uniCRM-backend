<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use App\Services\FileSave\ImageService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
  public function __construct(
    private ProductRepository $productRepository,
    private ImageService $imageService

  ) {}

  public function getAllProducts(): Collection
  {
    return $this->productRepository->getAll();
  }

  public function getPaginatedProducts(): LengthAwarePaginator
  {
    return $this->productRepository->getPaginated();
  }

  public function getProductById(int $id): ?Product
  {
    return $this->productRepository->findById($id);
  }

  public function createProduct(array $data): Product
  {
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $data['image'] = $this->imageService->uploadImage($data['image'], 'products');
    }

    return $this->productRepository->create($data);
  }

  public function updateProduct(Product $product, array $data)
  {
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $data['image'] = $this->imageService->updateImage($data['image'], $product->image);
    }

    return $this->productRepository->update($product, $data);
  }

  public function deleteProduct(Product $product): bool
  {
    $this->imageService->deleteImage($product->image);

    return $this->productRepository->delete($product);
  }
}
