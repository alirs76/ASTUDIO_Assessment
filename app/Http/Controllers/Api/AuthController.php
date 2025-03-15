<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

	public function register(RegisterRequest $request)
	{
		$user = new User();

		$user->fill($request->toArray());

		if ($user->save()) {
			return response()->json([
				'success' => true,
				'message' => 'User created successfully',
				'data' => new UserResource($user)
			], Response::HTTP_CREATED);
		}
		return response()->json([
			'success' => false,
			'message' => 'User not created',
			'data' => null
		], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function login(LoginRequest $request)
	{
		$user = User::where('email', $request->email)->first();

		if ($user && Hash::check($request->password, $user->password)) {
			$token = $user->createToken('Token Name')->accessToken;
			return response()->json([
				'success' => true,
				'message' => 'User logged in successfully',
				'data' => [
					'user' => new UserResource($user),
					'token' => $token
				]
			], Response::HTTP_OK);
		}
		return response()->json([
			'success' => false,
			'message' => 'Invalid credentials',
			'data' => null
		], Response::HTTP_UNAUTHORIZED);
	}

	public function logout()
	{
		if (Auth::check() && Auth::user()->token()->revoke()) {
			return response()->json([
				'success' => true,
				'message' => 'User logged out successfully',
				'data' => null
			], Response::HTTP_OK);
		}
		return response()->json([
			'success' => false,
			'message' => 'User not logged in',
			'data' => null
		], Response::HTTP_UNAUTHORIZED);
	}
}
