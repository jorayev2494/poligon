<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{

    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->middleware('guest:api', ['only' => ['register', 'login']]);
        $this->middleware('auth:api', ['only' => ['refresh', 'logout']]);
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|alpha',
            'last_name' => 'required|string|alpha',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $result = $this->service->register($validated);

        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exceptions\Auth\InvalidCredentialsException
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        $result = $this->service->login($validated);

        return response()->json($result);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $result = $this->service->logout();

        return response()->json($result, Response::HTTP_ACCEPTED);
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $result = $this->service->refresh();

        return response()->json($result);
    }
}
