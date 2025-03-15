<?php

namespace Database\Seeders;

use App\Enums\AttributeType;
use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Attribute::firstOrCreate([
			'name' => 'department',
			'type' => AttributeType::TEXT
		]);
		Attribute::firstOrCreate([
			'name' => 'start_date',
			'type' => AttributeType::DATE
		]);
		Attribute::firstOrCreate([
			'name' => 'end_date',
			'type' => AttributeType::DATE
		]);
	}
}
