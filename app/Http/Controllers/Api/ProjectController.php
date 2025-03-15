<?php

namespace App\Http\Controllers\Api;

use App\CustomFilter\AttributeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\CreateProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
	public function list(Request $request)
	{
		$projects = QueryBuilder::for(Project::class)
			->with('attributes')
			->allowedFilters([
				'name',
				AllowedFilter::exact('status'),
				AllowedFilter::custom('attribute', new AttributeFilter()),
			])
			->paginate(perPage: $request->perPage ?? 15, page: $request->page ?? 1);
		return response()->json(
			[
				'data' => ProjectResource::collection($projects->items()),
				'currentPage' => $projects->currentPage(),
				'perPage' => $projects->perPage(),
				'total' => $projects->total(),
				'lastPage' => $projects->lastPage(),
			]
		);
	}

	public function create(CreateProjectRequest $request)
	{
		$project = new Project();
		$project->fill($request->toArray());

		if (!$project->save()) {
			return response()->json(['message' => 'Project creation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		$this->syncUsers($request, $project);
		$this->syncAttributes($request, $project);

		return response()->json(new ProjectResource($project), Response::HTTP_CREATED);
	}

	public function show(Project $project)
	{
		$project->load(['users', 'timesheets', 'attributes']);
		return response()->json(new ProjectResource($project));
	}

	public function update(UpdateProjectRequest $request, Project $project): Response
	{
		$project->fill($request->toArray());

		if (!$project->save()) {
			return response()->json(['message' => 'Project update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

		$this->syncUsers($request, $project);
		$this->syncAttributes($request, $project);

		return response()->json(new ProjectResource($project));
	}

	private function syncUsers(Request $request, Project $project): void
	{
		if (!$request->has('user_ids')) {
			return;
		}

		$project->users()->sync($request->get('user_ids'));
	}

	private function syncAttributes(Request $request, Project $project): void
	{
		if (!$request->has('attributes')) {
			return;
		}

		$project->attributes()->sync(
			$this->prepareAttributes($request->get('attributes'))
		);
	}

	private function prepareAttributes(array $attributes): array
	{
		return collect($attributes)
			->keyBy('id')
			->map(fn(array $attribute) => ['value' => $attribute['value']])
			->toArray();
	}

	public function delete(Project $project)
	{
		if ($project->delete()) {
			return response()->json(['message' => 'Project deleted successfully']);
		}
		return response()->json(['message' => 'Project deletion failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
