<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function register(RegisterRequest $request) {
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function login(LoginRequest $request) {
        $data = $request->only('email', 'password');
        if (!Auth::attempt($data)) {
            return response()->json([
                'msg' => 'user_not_found'
            ], 401);
        }
        $user   = User::where('email', $request->email)->firstOrFail();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'msg'       => 'login_success',
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return response()->json([
            'msg' => 'logout_successfull'
        ]);
    }
}
