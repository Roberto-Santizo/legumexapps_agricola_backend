<?php
namespace App\Interfaces\Permissions;

interface PermissionServiceInterface
{
    public function createPermission(array $data);
    public function getPermissions(string | null $limit);
}