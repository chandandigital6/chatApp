<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{

 public function save(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $request->user()->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'FCM token saved successfully',
        ]);
    }
    
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9]{7,15}$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone_number' => $data['phone_number'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('User');
        }

        event(new Registered($user));

        $token = $user->createToken($data['device_name'] ?? 'api-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'token_type' => 'Bearer',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        $token = $user->createToken($data['device_name'] ?? 'api-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token_type' => 'Bearer',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful',
        ]);
    }
}