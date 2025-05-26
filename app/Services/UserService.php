<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(array $filters = [], bool $withTrashed = false)
    {
        return $this->userRepository->getAll($filters, $withTrashed);
    }

    public function findById($id, bool $withTrashed = false): ?User
    {
        return $this->userRepository->findById($id, $withTrashed);
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $user = $this->userRepository->create($data);

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['updated_by'] = Auth::id();
        $user = $this->userRepository->update($user, $data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    public function bulkDelete(array $userIds): void
    {
        foreach ($userIds as $userId) {
            $user = $this->findById($userId);
            if ($user) {
                $this->deleteUser($user);
            }
        }
    }

    public function restoreUser($id): ?User
    {
        return $this->userRepository->restore($id);
    }

    public function exportUsers(array $filters = []): Collection
    {
        return $this->userRepository->getAllForExport($filters);
    }
}