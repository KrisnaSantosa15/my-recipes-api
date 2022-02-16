<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function register(Request $request)
	{
		$validatedData = $request->validate([
			'username' => 'required|string|max:255|unique:users',
			'full_name' => 'required|string|max:255',
			'password' => 'required|string|min:8|confirmed',
		]);

		$user = User::create([
			'username' => $validatedData['username'],
			'full_name' => $validatedData['full_name'],
			'password' => Hash::make($validatedData['password']),
		]);

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'access_token' => $token,
			'message' => 'Succesfully registered',
			'token_type' => 'Bearer',
		], 200);
	}

	// make function login to authenticate user using json web token
	public function login(Request $request)
	{
		$validatedData = $request->validate([
			'username' => 'required|string|max:255',
			'password' => 'required|string|min:8',
		]);

		$user = User::where('username', $validatedData['username'])->first();

		if (!$user) {
			return response()->json([
				'message' => 'User not found',
			], 404);
		}

		if (!Hash::check($validatedData['password'], $user->password)) {
			return response()->json([
				'message' => 'Invalid credentials',
			], 401);
		}

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'user' => $user,
			'access_token' => $token,
			'message' => 'Succesfully logged in',
			'token_type' => 'Bearer',
		], 200);
	}

	// make logout function to delete json web token
	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();

		return response()->json([
			'message' => 'Successfully logged out',
		]);
	}
}
