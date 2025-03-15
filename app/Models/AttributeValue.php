<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AttributeValue extends Pivot
{
	protected $fillable = ['attribute_id', 'entity_id', 'value','created_at','updated_at'];

	public function attribute(): BelongsTo
	{
		return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
	}

	public function project(): BelongsTo
	{
		return $this->belongsTo(Project::class, 'entity_id', 'id');
	}
}
