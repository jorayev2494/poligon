<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

/**
 * class ProfileService
 * @package App\Services
 */
class ProfileService
{
    /**
     * @return User
     */
    public function profile(): User
    {
        return auth()->user();
    }
}
