<?php

namespace App\Services\Settings\Role;

use App\Helpers\Pagination\PaginationHelper;
use App\Http\Resources\RoleResource;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class RoleService
{
  public function __construct(protected RoleRepositoryInterface $roleRepository) {}

  public function getAllRoles(): Collection
  {
    return $this->roleRepository->all();
  }

  public function getRolesWithPermissions(): Collection
  {
    return $this->roleRepository->withPermissions();
  }

  public function getPaginatedRoles(int $perPage = 20)
  {
    $roles = $this->roleRepository->paginate($perPage);

    return [
      'roles' => RoleResource::collection($roles),
      'meta'  => PaginationHelper::format($roles),
    ];
  }

  public function getRoleById(int $id): Role
  {
    $role = $this->roleRepository->find($id);

    if (!$role) throw new ModelNotFoundException('Role not found');

    return $role;
  }

  public function createRole(array $data): Role
  {
    if ($this->roleRepository->findByName($data['name'])) {
      throw ValidationException::withMessages([
        'name' => 'Role with this name already exists'
      ]);
    }

    $role = $this->roleRepository->create([
      'name' => $data['name'],
      'guard_name' => $data['guard_name'] ?? 'web',
    ]);

    if (isset($data['permissions']) && is_array($data['permissions'])) {
      $this->assignPermissionsToRole($role, $data['permissions']);
    }

    return $role->fresh('permissions');
  }

  public function updateRole(int $id, array $data): Role
  {
    $role = $this->getRoleById($id);

    if (isset($data['name']) && $data['name'] !== $role->name) {
      $existingRole = $this->roleRepository->findByName($data['name']);
      if ($existingRole && $existingRole->id !== $id) {
        throw ValidationException::withMessages([
          'name' => 'Role with this name already exists'
        ]);
      }
    }

    $this->roleRepository->update($id, [
      'name' => $data['name'] ?? $role->name,
      'guard_name' => $data['guard_name'] ?? $role->guard_name,
    ]);

    if (isset($data['permissions']) && is_array($data['permissions'])) {
      $this->syncPermissionsToRole($role, $data['permissions']);
    }

    return $role->fresh('permissions');
  }

  public function deleteRole(int $id): bool
  {
    $role = $this->getRoleById($id);

    if ($role->users()->count() > 0) {
      throw ValidationException::withMessages([
        'role' => 'Cannot delete role that is assigned to users'
      ]);
    }

    return $this->roleRepository->delete($id);
  }

  public function assignPermissionsToRole(Role $role, array $permissions): Role
  {
    $validPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
    $role->givePermissionTo($validPermissions);

    return $role->fresh('permissions');
  }

  public function syncPermissionsToRole(Role $role, array $permissions): Role
  {
    $validPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
    $role->syncPermissions($validPermissions);

    return $role->fresh('permissions');
  }

  public function removePermissionFromRole(Role $role, string $permission): Role
  {
    $role->revokePermissionTo($permission);

    return $role->fresh('permissions');
  }
}
