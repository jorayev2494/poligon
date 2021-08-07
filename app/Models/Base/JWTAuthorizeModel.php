<?php

declare(strict_types=1);

namespace App\Models\Base;
use Illuminate\Foundation\Auth\User as Authenticatable;

class JWTAuthorizeModel extends Authenticatable
{

    /**
     * @param string $value
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->attributes['first_name']} {$this->attributes['last_name']}";
    }

}
