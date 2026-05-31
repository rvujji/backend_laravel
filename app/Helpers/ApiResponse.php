<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(
        string $message = '',
        mixed $data = null,
        int $status = 200
    ) {

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null
        ], $status);
    }

    public static function error(
        string $message = '',
        mixed $errors = null,
        int $status = 400
    ) {

        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors
        ], $status);
    }

    public static function paginated(
        string $message,
        $paginator,
        $resourceClass
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,

            'data' => [

                'current_page' =>
                $paginator->currentPage(),

                'last_page' =>
                $paginator->lastPage(),

                'per_page' =>
                $paginator->perPage(),

                'total' =>
                $paginator->total(),

                'data' =>
                $resourceClass::collection(
                    $paginator->items()
                ),
            ],

            'errors' => null,
        ]);
    }
}
