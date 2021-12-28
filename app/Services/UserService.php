<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\FileTrait;

class UserService
{

    use FileTrait;

    /**
    * @var UserRepository $repository
    */
    public UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        if (array_key_exists('avatar', $data)) {
            ['full_cdn_path' => $data['avatar']] = $this->uploadFile(User::AVATAR_PATH, $data['avatar']);
        }

        return $this->repository->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUser(int $id, array $data): User
    {
        return $this->repository->update($id, $data);
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        /** @var User $foundUser */
        $foundUser = $this->repository->find($id);

        if ($foundUser->avatar) {
            $this->deleteFile(User::AVATAR_PATH, $foundUser->getRawOriginal('avatar'));
        }

        $foundUser->delete();
    }
}
