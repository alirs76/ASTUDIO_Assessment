<?php

namespace App\Rules;

use App\Enums\AttributeType;
use App\Models\Attribute;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class AttributeValueRule implements ValidationRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @param Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
	 * @return void
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (isset($value['id'], $value['value']) === false) {
			$fail('The attribute ID and value are required.');
			return;
		}

		$attribute = Attribute::find($value['id']);

		if (empty($attribute)) {
			$fail("Attribute with id: {$value['id']} not found");
			return;
		}

		if (strlen($value['value']) > 255) {
			$fail("Value for Attribute with id: {$value['id']} must be less than 255 characters");
			return;
		}
		switch ($attribute->type->value) {
			case AttributeType::TEXT->value:
				if (is_string($value['value']) === false) {
					$fail("Value for Attribute with id: {$value['id']} must be string");
				}
				break;
			case AttributeType::DATE->value:
				if (Carbon::hasFormat($value['value'], 'Y-m-d') === false) {
					$fail("Value for Attribute with id: {$value['id']} must be date with the format of Y-m-d");
				}
				break;
			case AttributeType::NUMBER->value:
				if (is_numeric($value['value']) === false) {
					$fail("Value for Attribute with id: {$value['id']} must be numeric");
				}
				break;
			case AttributeType::SELECT->value:
				//I did not understand of this one
				break;
		}
	}
}
