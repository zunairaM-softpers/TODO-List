<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

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
            'email' => 'required|string|email|exists:users,email'
        ]);

        $user = ModelsUser::where('email', $request->email)->first();

        $token = $this->generateToken($user);

        return ['token' => $token->plainTextToken];
    }

    public function generateToken($user)
    {
        // Generating access token for registered users once login is successful
        return $user->createToken("access-token");
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "message" => "Logged out successfully."
        ]);
    }
}
