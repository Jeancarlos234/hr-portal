<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->input('search'),
            'is_active' => $request->input('is_active'),
            'sort' => $request->input('sort', 'name'),
        ];

        $perPage = min((int) $request->input('per_page', 15), 100);

        $roles = $this->roleService->getAll($filters, $perPage);

        return ApiResponse::collection(
            RoleResource::collection($roles),
            'Roles retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->roleService->create($request->validated());

        return ApiResponse::resource(
            new RoleResource($role),
            'Role created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $role = $this->roleService->getById($id);

        return ApiResponse::resource(
            new RoleResource($role),
            'Role retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role = $this->roleService->update($role, $request->validated());

        return ApiResponse::resource(
            new RoleResource($role),
            'Role updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        try {
            $this->roleService->delete($role);
            return ApiResponse::success(null, 'Role deleted successfully.', [], 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), null, $e->getCode() ?: 409);
        }
    }
}