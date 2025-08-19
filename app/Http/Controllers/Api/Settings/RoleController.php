<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Role\IndexRoleRequest;
use App\Http\Requests\Settings\Role\StoreRoleRequest;
use App\Http\Requests\Settings\Role\SyncPermissionRequest;
use App\Http\Requests\Settings\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\Settings\Role\RoleService;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(protected RoleService $roleService) {}

    public function roles()
    {
        return RoleResource::collection(Role::all());
    }

    public function index(IndexRoleRequest $request)
    {
        try {
            $result = $this->roleService->getPaginatedRoles($request->per_page);

            return $this->successResponse($result);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            $role = $this->roleService->createRole($request->validated());

            return $this->successResponse(new RoleResource($role));
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function show(Role $role)
    {
        try {
            return $this->successResponse(new RoleResource($role->load('permissions')));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $role = $this->roleService->updateRole($role->id, $request->validated());

            return $this->successResponse(new RoleResource($role));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role->id);

            return $this->successResponse(null, 'Role deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function assignPermissions(SyncPermissionRequest $request, Role $role)
    {
        try {
            $role = $this->roleService->assignPermissionsToRole($role, $request->permissions);

            return $this->successResponse(new RoleResource($role));
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function syncPermissions(SyncPermissionRequest $request, Role $role)
    {
        try {
            $role = $this->roleService->syncPermissionsToRole($role, $request->permissions);

            return $this->successResponse(new RoleResource($role), 'Permissions synced successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
