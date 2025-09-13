<?php

namespace App\Services\FileSave;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
  public function uploadImage(UploadedFile $image, string $folder): string
  {
    $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
    $path = $image->storeAs("{$folder}", $filename);
    return str_replace('public/', 'storage/', $path);
  }

  public function deleteImage(?string $imagePath): bool
  {
    if (!$imagePath) return true;

    $path = str_replace('storage/', 'public/', $imagePath);
    return Storage::delete($path);
  }

  public function updateImage(?UploadedFile $newImage, ?string $oldImagePath, string $folder = 'products'): ?string
  {
    if (!$newImage) return $oldImagePath;

    $this->deleteImage($oldImagePath);
    return $this->uploadImage($newImage, $folder);
  }
}
