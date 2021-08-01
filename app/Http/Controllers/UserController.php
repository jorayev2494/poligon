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
        $data = array_merge($request->all(), [
            'email' => random_int(1, 100) . $request->get('email')
        ]);

        $storedUser = $this->service->repository->create($data);

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
        $updatedUser = $this->service->updateUser($id, $request->all());

        return response()->json(UserResource::make($updatedUser->refresh()), Response::HTTP_ACCEPTED);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $this->service->repository->delete($id);

        return response()->noContent();
    }
}
