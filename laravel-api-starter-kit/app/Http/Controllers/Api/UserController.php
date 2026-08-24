<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'email_verified' => $request->input('email_verified'),
            'created_from' => $request->input('created_from'),
            'created_to' => $request->input('created_to'),
            'role' => $request->input('role'),
            'sort' => $request->input('sort', '-created_at'),
        ];

        $perPage = min((int) $request->input('per_page', 15), 100);

        $users = $this->userService->getAll($filters, $perPage);

        return ApiResponse::collection(
            UserResource::collection($users),
            'Users retrieved successfully.'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return ApiResponse::resource(
            new UserResource($user),
            'User created successfully.',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getById($id);

        return ApiResponse::resource(
            new UserResource($user),
            'User retrieved successfully.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user = $this->userService->update($user, $request->validated());

        return ApiResponse::resource(
            new UserResource($user),
            'User updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $this->userService->delete($user);

        return ApiResponse::success(null, 'User deleted successfully.', [], 200);
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(int $id): JsonResponse
    {
        $user = $this->userService->restore($id);

        return ApiResponse::resource(
            new UserResource($user),
            'User restored successfully.'
        );
    }
}