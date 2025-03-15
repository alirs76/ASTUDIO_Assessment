<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
	public function list(Request $request)
	{
		$users = User::paginate(page: $request->page ?? 1, perPage: $request->perPage ?? 15);
		return response()->json(
			[
				'data' => UserResource::collection($users->items()),
				'currentPage' => $users->currentPage(),
				'perPage' => $users->perPage(),
				'total' => $users->total(),
				'lastPage' => $users->lastPage(),
			]
		);
	}

	public function show(User $user)
	{
		return response()->json(new UserResource($user));
	}

	public function update(UpdateUserRequest $request, User $user)
	{
		$user->fill($request->toArray());

		if ($user->save()) {
			return response()->json(new UserResource($user));
		}
		return response()->json(['message' => 'Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function delete(Request $request, User $user)
	{

		if (Auth::user()->cannot('delete', $user)) {
			return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
		}
		if ($user->delete()) {
			return response()->json(['message' => 'User deleted']);
		}
		return response()->json(['message' => 'Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
