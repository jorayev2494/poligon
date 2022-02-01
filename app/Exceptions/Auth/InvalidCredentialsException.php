<?php

declare(strict_types=1);

namespace App\Exceptions\Auth;

use App\Exceptions\Base\BaseException;
use Illuminate\Http\Response;

/**
 * class InvalidCredentialsException
 * @package App\Exceptions\Auth
 */
class InvalidCredentialsException extends BaseException
{
    public $message = 'Invalid credentials';

    public $code = Response::HTTP_BAD_REQUEST;
}
