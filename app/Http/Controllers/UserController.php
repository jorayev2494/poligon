<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\{JsonResponse, Request, Response};

class UserController extends Controller
{

    /**
    * @var UserService $service
    */
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->service->repository->get();

        return response()->json(UserResource::collection($users));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'string|min:2|max:50',
            'last_name' => 'string|min:2|max:50',
            'avatar' => 'file|mimes:jpg,jpeg,png',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6|max:20',
            'phone' => 'required',
        ]);

        $storedUser = $this->service->create($validated);

        return response()->json(UserResource::make($storedUser));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $foundUser = $this->service->repository->find($id);

        return response()->json(UserResource::make($foundUser));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'string|min:2|max:50',
            'last_name' => 'string|min:2|max:50',
            'avatar' => 'file|mimes:jpg,jpeg,png',
            'email' => "email|unique:users,email,{$id},id",
            'password' => 'string|confirmed|min:6|max:20',
            'phone' => "string",
        ]);

        $updatedUser = $this->service->updateUser($id, $validated);

        return response()->json(UserResource::make($updatedUser->refresh()), Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $this->service->delete($id);

        return response()->noContent();
    }
}
