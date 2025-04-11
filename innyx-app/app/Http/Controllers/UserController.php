<?php

namespace App\Http\Controllers;

use App\Data\Auth\LoginData;
use App\Data\Auth\UserData;
use App\Data\Auth\RegisterData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service)
    {
    }

    public function get_preview (Request $request)
    {
        return response()->json($this->service->preview($request->user()), 200);
    }

    public function index(Request $request): JsonResponse
    {
        $page = $request->integer('page', 1);
        $search = $request->string('search', '');
        $data = $this->service->list($page, $search);
        return response()->json($data);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());
        return response()->json(UserData::fromModel($user));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $updatedUser = $this->service->update($user, $request->validated());
        return response()->json(UserData::fromModel($updatedUser));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);
        return response()->json([], 204);
    }

    public function resetPass(Request $request)
    {
        return response()->json($this->service->resetPassword($request->get('id')), 200);
    }
}
