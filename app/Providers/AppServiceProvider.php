<?php

namespace App\Providers;

use App\Models\Retailer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\Lp;
use App\Models\ManitobaReport;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        $adminRoles = Role::where([
            ['name', '!=', 'Retailer'],
            ['name', '!=', 'Lp']
        ])->get();

        $reportsCount = Retailer::sum('report_count');

        view()->composer('*', function ($view) use ($adminRoles, $reportsCount) {
            $view->with('adminRoles', $adminRoles);
            $view->with('reportsCount', $reportsCount);
        });

        view()->composer('admin.reports.cova.index', function ($view) use ($reportsCount) {
            $view->with('reportsCount', $reportsCount);
        });
    }
}
