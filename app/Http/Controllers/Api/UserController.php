<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Notifications\ResetPassword;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $this->userRepository->update($user->id, $data);
        return response()->json(['message' => 'updated successfully']);

    }

    public function resetPassword(ResetPasswordRequest $request )
    {
        $data = $request->validated();
        $user = Auth::user();
        if(! Hash::check($data['old_password'], $user->password)) {
            return response()->json(['message' => 'invalid old password'], 403);
        }

        $this->userRepository->update($user->id,['password' => bcrypt($data['new_password'])]);
        return response()->json(['message' => 'updated successfully']);

    }
}
