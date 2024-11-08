<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepositoryImpl implements UserRepositoryInterface
{
    public function create(array $data)
    {
        // $data['password'] = Hash::make($data['password']);

        return User::create($data);
     
    }

}