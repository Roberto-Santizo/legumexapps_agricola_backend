<?php

namespace App\Services\Users;

use App\Interfaces\Users\UserServiceInterface;
use App\Models\User;

class UserService implements UserServiceInterface
{
    public function createUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return $user;
    }
}
