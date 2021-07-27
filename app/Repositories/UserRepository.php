<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Base\BaseModelRepository;

class UserRepository extends BaseModelRepository
{
    protected function getModel(): string
    {
        return User::class;
    }
}
