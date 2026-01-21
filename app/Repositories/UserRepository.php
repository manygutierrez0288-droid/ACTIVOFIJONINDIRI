<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        return $this->model->with('roles')->get();
    }

    public function syncRoles(User $user, array $roles)
    {
        return $user->roles()->sync($roles);
    }
}
