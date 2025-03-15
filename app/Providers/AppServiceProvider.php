<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Timesheet;
use App\Models\User;
use App\Policies\ProjectPolicy;
use App\Policies\TimesheetPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
	    $this->policies();
    }


	public function policies(): void
	{
		Gate::policy(User::class, UserPolicy::class);
		Gate::policy(Project::class, ProjectPolicy::class);
		Gate::policy(Timesheet::class, TimesheetPolicy::class);
	}
}
