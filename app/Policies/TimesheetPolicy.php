<?php

namespace App\Policies;

use App\Models\Timesheet;
use App\Models\User;

class TimesheetPolicy
{
    public function view(User $user, Timesheet $timesheet): bool
    {
        return $user->id === $timesheet->user_id;
    }

    public function update(User $user, Timesheet $timesheet): bool
    {
        return $user->id === $timesheet->user_id;
    }

    public function delete(User $user, Timesheet $timesheet): bool
    {
        return $user->id === $timesheet->user_id;
    }
}
