<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
	use HasFactory, Notifiable, HasApiTokens;

	protected $fillable = [
		'first_name',
		'last_name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
	protected $casts = [
            'password' => 'hashed',
        ];

	public function projects(): BelongsToMany
	{
		return $this->belongsToMany(
			Project::class,
			ProjectUserPivot::class,
			'user_id',
			'project_id',
			'id',
			'id'
		);
	}

	public function timesheets(): HasMany
	{
		return $this->hasMany(Timesheet::class, 'user_id', 'id');
	}
}
