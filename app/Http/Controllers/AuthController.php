<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    public function register(RegisterRequest $request)
    {
       $data = $request->validated();
       $user = $this->userRepository->store($data);

       return response()->json(["message" => "Registered successfully!"], 200);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $guard = $this->getGuard($request);
        if(!Auth::attempt($data)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $data['email'])->first();
         if ($guard === 'web') {
            // For web authentication
            Auth::guard('web')->login($user);
            
            return [
                'user' => $user,
                'redirect' => route('home'),
            ];
        } else {
            // For Api authentication
            $token = $token = $user->createToken('auth_token', ['*'])->plainTextToken;
            
            return [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ];
        }
        
    }

    public function logout(Request $request)
    {
        $guard = $this->getGuard($request);
        if ($guard === 'web') {
            Auth::logout();
            return redirect()->route('login');
        } else {
            Auth::user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out']);
        }

    }


    public function loginView()
    {
        return view('login');
    }

    protected function getGuard(Request $request): string
    {
        // Check if request is for API
        if ($request->is('api/*') || $request->expectsJson() || $request->wantsJson()) {
            return 'api';
        }
        
        return 'web';
    }
}

