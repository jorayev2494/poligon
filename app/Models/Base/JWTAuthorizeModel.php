<?php

namespace App\Models\Base;
use Illuminate\Foundation\Auth\User as Authenticatable;

class JWTAuthorizeModel extends Authenticatable
{

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = bcrypt($value);
    }

}
