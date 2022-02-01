<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;

/**
 * class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    private ?User $authUser;

    private ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->middleware('auth');
        $this->authUser = auth()->guard('api')->user();
        $this->service = $service;
    }

    public function profile(): JsonResponse
    {
        $result = $this->service->profile();

        return response()->json($result);
    }
}
