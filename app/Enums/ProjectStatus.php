<?php

namespace App\Enums;

enum ProjectStatus: string
{
	case ACTIVE = 'active';
	case INACTIVE = 'inactive';
	case COMPLETED = 'completed';
	case ON_HOLD = 'on_hold';

	public static function values(): array { return array_column(self::cases(), 'value'); }
}