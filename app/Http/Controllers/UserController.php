<?php

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        $users = $this->service->repository->get();

        return response()->json(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): JsonResponse
    {
        $foundUser = $this->service->repository->find($id);

        return response()->json(UserResource::make($foundUser));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $updatedUser = $this->service->updateUser($id, $request->all());

        return response()->json(UserResource::make($updatedUser->refresh()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id): Response
    {
        $this->service->repository->delete($id);

        return response()->noContent();
    }
}
