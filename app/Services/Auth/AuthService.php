<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\Auth\IncalidCredentialsException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Repositories\UserRepository;

/**
 * class AuthService
 * @package App\Services\Auth
 */
class AuthService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return string
     */
    public function register(array $data): array
    {
        $data['phone'] = '';
        $this->userRepository->create($data);

        return ['message' => 'Successfully registered'];
    }

    /**
     * @param array $credentials
     * @return array
     * @throws InvalidCredentialsException
     */
    public function login(array $credentials): array
    {
        /** @var string|null $token */
        if (!$token = auth()->attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        return $this->authResponse($token);
    }

    /**
     * @return string[]
     */
    public function logout(): array
    {
        auth()->guard('api')->logout();

        return ['message' => 'Successfully registered'];
    }

    /**
     * @return array
     */
    public function refresh(): array
    {
        return $this->authResponse(auth()->guard('api')->refresh());
    }

    /**
     * @param string $accessToken
     * @return array
     */
    private function authResponse(string $accessToken): array
    {
        return [
            'access_token' => $accessToken,
            'token_type' => 'bearer',
            'expire_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
