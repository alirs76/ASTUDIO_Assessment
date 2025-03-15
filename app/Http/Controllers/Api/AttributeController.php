<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\CreateAttributeRequest;
use App\Http\Requests\Attribute\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeController extends Controller
{
	public function list(Request $request)
	{
		$attributes = Attribute::paginate(page: $request->page ?? 1, perPage: $request->perPage ?? 15);
		return response()->json(
			[
				'data' => AttributeResource::collection($attributes->items()),
				'currentPage' => $attributes->currentPage(),
				'perPage' => $attributes->perPage(),
				'total' => $attributes->total(),
				'lastPage' => $attributes->lastPage(),
			]
		);
	}

	public function create(CreateAttributeRequest $request)
	{
		$attribute = new Attribute();
		$attribute->fill($request->toArray());
		if ($attribute->save()) {
			return response()->json(new AttributeResource($attribute), Response::HTTP_CREATED);
		}
		return response()->json(['message' => 'Attribute creation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function show(Attribute $attribute)
	{
		return response()->json(new AttributeResource($attribute));
	}

	public function update(UpdateAttributeRequest $request, Attribute $attribute)
	{
		$attribute->fill($request->toArray());
		if ($attribute->save()) {
			return response()->json(new AttributeResource($attribute));
		}
		return response()->json(['message' => 'Attribute update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function delete(Attribute $attribute)
	{
		if ($attribute->delete()) {
			return response()->json(['message' => 'Attribute deleted successfully']);
		}
		return response()->json(['message' => 'Attribute deletion failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
