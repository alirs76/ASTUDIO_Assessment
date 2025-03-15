<?php

namespace App\Models;

use App\Enums\AttributeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
	protected $fillable = ['name', 'type'];
	protected $casts = [
		'type' => AttributeType::class,
	];

	public function values(): HasMany
	{
		return $this->hasMany(AttributeValue::class, 'attribute_id', 'id');
	}

	public function projects(): BelongsToMany
	{
		return $this->belongsToMany(
			Project::class,
			AttributeValue::class,
			'attribute_id',
			'entity_id',
			'id',
			'id',
		);
	}
}
