<?php

namespace App\Http\Controllers\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permissions\CreatePermissionRequest;
use App\Http\Resources\Permissions\PaginatedPermissionsResource;
use App\Http\Resources\Permissions\PermissionsResource;
use App\Interfaces\Permissions\PermissionServiceInterface;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PermissionServiceInterface $service)
    {
        try {
            $limit = $request->query('limit');
            $permissions = $service->getPermissions($limit);

            $data = $limit ? new PaginatedPermissionsResource($permissions) : PermissionsResource::collection($permissions);

            return response()->json([
                'statusCode' => 201,
                'message' => 'Permisos Obtenidos Exitosamente',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request, PermissionServiceInterface $service)
    {
        try {
            $data = $request->validated();
            $service->createPermission($data);

            return response()->json([
                'statusCode' => 201,
                'message' => 'Permiso Creado Exitosamente',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => $th->getCode(),
                'message' => $th->getMessage(),
            ]);
        }
    }
}
