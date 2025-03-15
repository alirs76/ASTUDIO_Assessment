<?php

namespace App\Http\Requests\Attribute;

use App\Enums\AttributeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
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
			'name' => ['string', 'unique:attributes,name,'.$this->route('attribute')->id],
			'type' => [Rule::in(AttributeType::values())],
		];
	}

	public function messages()
	{
		return [
			'name.string' => 'Attribute name must be a string.',
			'name.unique' => 'Attribute with this name already exists.',

			'type.in' => 'Invalid attribute type.',
		];
	}
}
