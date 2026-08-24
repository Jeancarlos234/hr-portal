<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse
{
    /**
     * Success response.
     */
    public static function success(
        mixed $data = null,
        string $message = 'Operation successful.',
        array $meta = [],
        int $status = 200
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }

    /**
     * Error response.
     */
    public static function error(
        string $message = 'An error occurred.',
        mixed $errors = null,
        int $status = 500
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Validation error response.
     */
    public static function validationError(
        mixed $errors,
        string $message = 'Validation failed.'
    ): JsonResponse {
        return self::error($message, $errors, 422);
    }

    /**
     * Resource collection response with pagination.
     */
    public static function collection(
        ResourceCollection $collection,
        string $message = 'Data retrieved successfully.'
    ): JsonResponse {
        $paginated = $collection->response()->getData(true);
        
        return self::success(
            $paginated['data'],
            $message,
            [
                'pagination' => $paginated['meta'] ?? null,
                'links' => $paginated['links'] ?? null,
            ]
        );
    }

    /**
     * Resource response.
     */
    public static function resource(
        JsonResource $resource,
        string $message = 'Data retrieved successfully.',
        int $status = 200
    ): JsonResponse {
        return self::success($resource, $message, [], $status);
    }
}