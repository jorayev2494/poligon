<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\UserAvatarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * class UserAvatarController
 * @package App\Http\Controllers
 */
class UserAvatarController extends Controller
{
    /**
     * @var UserAvatarService $service
     */
    private UserAvatarService $service;

    public function __construct(UserAvatarService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate(['avatar' => 'required|file|mimes:jpg,jpeg,png']);
        $updatedAvatarUser = $this->service->update($id, $validated);

        return response()->json($updatedAvatarUser);
    }
}
