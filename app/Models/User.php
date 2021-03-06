<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Base\JWTAuthorizeModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends JWTAuthorizeModel
{
    use HasFactory, Notifiable;

    public const AVATAR_PATH = '/users/avatars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'avatar',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'full_name'
    ];

    /**
     * @return string|null
     */
    public function getAvatarAttribute(): ?string
    {
        $avatar = $this->getRawOriginal('avatar');

        return $avatar ? getenv('APP_URL') . $avatar : $avatar;
    }
}
