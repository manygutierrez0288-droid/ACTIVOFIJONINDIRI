<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function create(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user = $this->userRepository->create($data);

        if (isset($data['roles'])) {
            $this->userRepository->syncRoles($user, $data['roles']);
        }

        return $user;
    }

    public function update($id, array $data)
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->userRepository->update($id, $data);

        if ($user && isset($data['roles'])) {
            $this->userRepository->syncRoles($user, $data['roles']);
        }

        return $user;
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    public function getById($id)
    {
        return $this->userRepository->getById($id);
    }
}
