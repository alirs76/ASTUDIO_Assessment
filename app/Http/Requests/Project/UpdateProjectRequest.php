<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatus;
use App\Models\User;
use App\Rules\AttributeValueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
			'name' => ['string', 'max:255', 'unique:projects,name,'.$this->project?->id],
			'status' => [Rule::in(ProjectStatus::values())],

			'user_ids' => [Rule::exists(User::class, 'id')],

			'attributes' => ['array'],
			'attributes.*' => ['array', new AttributeValueRule()],
		];
	}

	public function messages()
	{
		return [
			'name.string' => 'The name must be a string.',
			'name.max' => 'The name may not be greater than :max characters.',

			'status.in' => 'The selected status is invalid.',

			'user_ids.exists' => 'One or more user IDs are invalid.',

			'attributes.array' => 'The attributes must be an array.',
			'attributes.*.array' => 'Each attribute must be an array.',
		];
	}
}
