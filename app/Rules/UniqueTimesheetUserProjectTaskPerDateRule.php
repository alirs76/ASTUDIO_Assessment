<?php

namespace App\Rules;

use App\Models\Timesheet;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

/**
 * user can not make timesheet for same project and task per date
 */
class UniqueTimesheetUserProjectTaskPerDateRule implements ValidationRule
{
	public function __construct(private Request $request) { }

	/**
	 * Run the validation rule.
	 *
	 * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$query = Timesheet::where([
			['user_id', $this->request->user()->id],
			['project_id', $this->request->project_id],
			['task_name', $this->request->task_name],
			['date', $this->request->date]
		]);

		$timesheet = $this->request->route("timesheet");
		if ($timesheet) {
			$query->where('id', '!=', $timesheet->id);
		}

		$query->exists() && $fail('You already have timesheet for this project and task in this date');
	}
}
