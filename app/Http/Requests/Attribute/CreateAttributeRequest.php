<?php

namespace App\Http\Requests\Attribute;

use App\Enums\AttributeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAttributeRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'unique:attributes,name'],
			'type' => ['required', Rule::in(AttributeType::values())],
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Attribute name is required.',
			'name.string' => 'Attribute name must be a string.',
			'name.unique' => 'Attribute with this name already exists.',

			'type.required' => 'Attribute type is required.',
			'type.in' => 'Invalid attribute type.',
		];
	}
}
