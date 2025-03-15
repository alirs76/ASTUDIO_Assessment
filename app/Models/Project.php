<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
	protected $fillable = [
		'name', 'status'
	];
	protected $hidden = [];
	protected $guarded = [];
	protected $casts = [
		'status' => ProjectStatus::class,
	];

	/**
	 * Get the user that owns the project.
	 */
	public function users(): BelongsToMany
	{
		return $this->belongsToMany(
			User::class,
			ProjectUserPivot::class,
			'project_id',
			'user_id',
			'id',
			'id'
		);
	}

	public function timesheets(): HasMany
	{
		return $this->hasMany(Timesheet::class, 'project_id', 'id');
	}

	public function attributes(): BelongsToMany
	{
		return $this->belongsToMany(
			Attribute::class,
			AttributeValue::class,
			'entity_id',
			'attribute_id',
			'id',
			'id'
		)->withPivot('value', 'id');
	}
}
