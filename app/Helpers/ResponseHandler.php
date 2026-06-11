<?php

namespace App\Helpers;

use App\Errors\ApiException;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponseHandler
{
    private static function paginatedResponse(mixed $data, string $message, int $statusCode)
    {
        $paginator = $data->resource;
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $paginator->items(),
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'perPage' => $paginator->perPage()
        ], $statusCode);
    }

    public static function success(mixed $data, string $message, int $statusCode)
    {
        if (is_object($data) && property_exists($data, 'resource') && $data->resource instanceof LengthAwarePaginator) {
            return self::paginatedResponse($data, $message, $statusCode);
        }

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error(\Throwable $error)
    {
        $statusCode = $error instanceof ApiException ? $error->getStatusCode() : 500;

        return response()->json([
            'statusCode' => $statusCode,
            'message' => $error->getMessage(),
            'data' => null
        ], $statusCode);
    }
}
