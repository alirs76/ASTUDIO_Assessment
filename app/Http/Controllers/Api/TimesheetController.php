<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Timesheet\CreateTimesheetRequest;
use App\Http\Requests\Timesheet\UpdateTimesheetRequest;
use App\Http\Resources\TimesheetResource;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TimesheetController extends Controller
{
	public function list(Request $request)
	{
		$timesheets = Timesheet::where('user_id', Auth::id())
			->paginate(page: $request->page ?? 1, perPage: $request->perPage ?? 15);
		return response()->json(
			[
				'data' => TimesheetResource::collection($timesheets->items()),
				'currentPage' => $timesheets->currentPage(),
				'perPage' => $timesheets->perPage(),
				'total' => $timesheets->total(),
				'lastPage' => $timesheets->lastPage(),
			]
		);
	}

	public function create(CreateTimesheetRequest $request)
	{
		$timesheet = new Timesheet();
		$timesheet->fill($request->toArray());
		if (Auth::user()->timesheets()->save($timesheet)) {
			return response()->json(new TimesheetResource($timesheet), Response::HTTP_CREATED);
		}
		return response()->json(['message' => 'Timesheet creation failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function show(Timesheet $timesheet)
	{
		if (Auth::user()->cannot('view', $timesheet)) {
			return response()->json(['message' => 'You are not allowed to delete this timesheet'], Response::HTTP_FORBIDDEN);
		}
		$timesheet->load(['user', 'project']);
		return response()->json(new TimesheetResource($timesheet));
	}

	public function update(UpdateTimesheetRequest $request, Timesheet $timesheet)
	{
		$timesheet->fill($request->toArray());
		if ($timesheet->save()) {
			return response()->json(new TimesheetResource($timesheet));
		}
		return response()->json(['message' => 'Timesheet update failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	public function delete(Timesheet $timesheet)
	{
		if (Auth::user()->cannot('delete', $timesheet)) {
			return response()->json(['message' => 'You are not allowed to delete this timesheet'], Response::HTTP_FORBIDDEN);
		}
		if ($timesheet->delete()) {
			return response()->json(['message' => 'Timesheet deleted successfully']);
		}
		return response()->json(['message' => 'Timesheet deletion failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
