<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
	protected $fillable = [
		'task_name', 'date', 'hours', 'user_id', 'project_id'
	];
	protected $casts = [
		'date' => 'date'
	];

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class, 'project_id', 'id');
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
