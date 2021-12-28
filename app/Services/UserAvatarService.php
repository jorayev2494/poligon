<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\FileTrait;

class UserAvatarService
{

    use FileTrait;

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function update(int $id, array $data): User
    {
        $foundUser = $this->repository->find($id);

        if (array_key_exists('avatar', $data)) {
            ['full_cdn_path' => $data['avatar']] = $this->updateFile(User::AVATAR_PATH, $foundUser->getRawOriginal('avatar'), $data['avatar']);
            $foundUser->update($data);
        }

        return $foundUser->refresh();
    }
}
