<?php

namespace App\Services\Permissions;
use App\Interfaces\Permissions\PermissionServiceInterface;
use App\Models\Permission;

class PermissionService implements PermissionServiceInterface
{
    public function createPermission(array $data)
    {
        $permission = Permission::create($data);
        return $permission;
    }

    public function getPermissions(string | null $limit)
    {
        $query = Permission::query();

        if($limit) {
            return $query->paginate($limit);
        }

        return $query->get();
    }
}