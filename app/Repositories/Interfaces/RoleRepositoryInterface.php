<?php

namespace App\Repositories\Interfaces;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
  public function all(): Collection;

  public function find(int $id): ?Role;

  public function create(array $data): Role;

  public function update(int $id, array $data): bool;

  public function delete(int $id): bool;

  public function findByName(string $name): ?Role;

  public function withPermissions(): Collection;

  public function paginate(int $perPage = 20);
}
