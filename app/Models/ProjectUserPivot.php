<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectUserPivot extends Pivot
{
	protected $table = 'project_user';
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class, 'project_id', 'id');
	}
}
