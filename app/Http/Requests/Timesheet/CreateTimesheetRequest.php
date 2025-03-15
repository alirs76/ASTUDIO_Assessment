<?php

namespace App\Http\Requests\Timesheet;

use App\Models\ProjectUserPivot;
use App\Rules\UniqueTimesheetUserProjectTaskPerDateRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateTimesheetRequest extends FormRequest
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
			'task_name' => ['required', 'string', 'max:255'],
			'date' => ['required', 'date'],
			'hours' => ['required', 'numeric', 'max:24'],
			'project_id' => [
				'required',
				new UniqueTimesheetUserProjectTaskPerDateRule($this),
				Rule::exists(ProjectUserPivot::class, 'project_id')
					->where('user_id', Auth::id())
			],
		];
	}

	public function messages()
	{
		return [
			'task_name.required' => 'The task_name field is required.',
			'task_name.string' => 'The task_name field must be a string.',
			'task_name.max' => 'The task_name field must not exceed 255 characters.',

			'date.required' => 'The date field is required.',
			'date.date' => 'The date field is invalid.',

			'hours.required' => 'The hours is required.',
			'hours.numeric' => 'The hours field must be a number.',
			'hours.max' => 'The hours field must not exceed 24.',

			'project_id.required' => 'The project field is required.',
			'project_id.exists' => 'The selected project is not available for you'

		];
	}
}
