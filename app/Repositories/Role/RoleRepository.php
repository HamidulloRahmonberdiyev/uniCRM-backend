<?php

namespace App\Repositories\Role;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleRepository implements RoleRepositoryInterface
{
  protected Role $model;

  public function __construct(Role $model)
  {
    $this->model = $model;
  }

  public function all(): Collection
  {
    return $this->model->all();
  }

  public function find(int $id): ?Role
  {
    return $this->model->find($id);
  }

  public function create(array $data): Role
  {
    return $this->model->create($data);
  }

  public function update(int $id, array $data): bool
  {
    $role = $this->find($id);

    if (!$role) throw new ModelNotFoundException('Role not found');

    return $role->update($data);
  }

  public function delete(int $id): bool
  {
    $role = $this->find($id);

    if (!$role) throw new ModelNotFoundException('Role not found');

    return $role->delete();
  }

  public function findByName(string $name): ?Role
  {
    return $this->model->where('name', $name)->first();
  }

  public function withPermissions(): Collection
  {
    return $this->model->with('permissions')->get();
  }

  public function paginate(int $perPage = 15)
  {
    return $this->model->paginate($perPage);
  }
}
