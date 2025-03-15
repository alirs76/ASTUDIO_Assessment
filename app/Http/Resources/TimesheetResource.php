<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id,
			'task_name' => $this->task_name,
			'date' => $this->date->format('Y-m-d'),
			'hours' => $this->hours,
			'user_id' => $this->user_id,
			'user' => new UserResource($this->whenLoaded('user')),
			'project_id' => $this->project_id,
			'project' => new ProjectResource($this->whenLoaded('project'))
		];
	}
}
