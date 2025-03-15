<?php

namespace App\Enums;

enum AttributeType: string
{
	case TEXT = 'text';
	case NUMBER = 'number';
	case DATE = 'date';
	case SELECT = 'select';

	public static function values(): array { return array_column(self::cases(), 'value'); }
}