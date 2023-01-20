<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = ModelsUser::create($data);

        event(new Registered($user));

        return response()->json([
            "message" => "Registration successful! Check you email to get email verification link."
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string'
        ]);

        $user = $this->validateUser($request);

        if (is_null($user)) {
            return response()->json([
                "Authentication failed"
            ], 403);
        }

        $token = $this->generateToken($user);

        return ['token' => $token];
    }

    public function validateUser($request)
    {
        return ModelsUser::where('email', $request->email)->where('password', $request->password)->first();
    }

    public function generateToken($user)
    {
        // Generating access token for registered users once login is successful
        return JWTAuth::fromUser($user);
    }

    public function logout(Request $request)
    {
        JWTAuth::parseToken()->invalidate(true);
        return response()->json([
            "message" => "Logged out successfully."
        ]);
    }
}
