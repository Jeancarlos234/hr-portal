<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionService $permissionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->input('search'),
            'module' => $request->input('module'),
            'is_active' => $request->input('is_active'),
            'sort' => $request->input('sort', 'module'),
        ];

        $perPage = min((int) $request->input('per_page', 15), 100);

        $permissions = $this->permissionService->getAll($filters, $perPage);

        return ApiResponse::collection(
            PermissionResource::collection($permissions),
            'Permissions retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->permissionService->create($request->validated());

        return ApiResponse::resource(
            new PermissionResource($permission),
            'Permission created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $permission = $this->permissionService->getById($id);

        return ApiResponse::resource(
            new PermissionResource($permission),
            'Permission retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, int $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $permission = $this->permissionService->update($permission, $request->validated());

        return ApiResponse::resource(
            new PermissionResource($permission),
            'Permission updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $this->permissionService->delete($permission);

        return ApiResponse::success(null, 'Permission deleted successfully.', [], 200);
    }
}