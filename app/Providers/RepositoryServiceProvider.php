<?php

namespace App\Providers;

use App\Interfaces\DashboardRepositoryInterface;
use App\Interfaces\Report\ReportStoreDateRepositoryInterface;
use App\Interfaces\Report\CarveOutRepositoryInterface;
use App\Interfaces\Report\CleanReportRepositoryInterface;
use App\Interfaces\Report\CleanSheetRepositoryInterface;
use App\Interfaces\Report\LpStatementRepositoryInterface;
use App\Interfaces\Report\ReportDeleteRepositoryInterface;
use App\Interfaces\Report\StoreRepositoryInterface;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Interfaces\User\AdminRepositoryInterface;
use App\Interfaces\User\LpRepositoryInterface;
use App\Interfaces\User\RetailerRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\Report\CarveOutRepository;
use App\Repositories\Report\CleanReportRepository;
use App\Repositories\Report\CleanSheetRepository;
use App\Repositories\Report\LpStatementRepository;
use App\Repositories\Report\ReportDeleteRepository;
use App\Repositories\Report\ReportStoreDateRepository;
use App\Repositories\Report\StoreRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\AdminRepository;
use App\Repositories\User\LpRepository;
use App\Repositories\User\RetailerRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(CleanReportRepositoryInterface::class, CleanReportRepository::class);
        $this->app->bind(LpStatementRepositoryInterface::class, LpStatementRepository::class);
        $this->app->bind(LpRepositoryInterface::class, LpRepository::class);
        $this->app->bind(RetailerRepositoryInterface::class, RetailerRepository::class);
        $this->app->bind(CleanSheetRepositoryInterface::class, CleanSheetRepository::class);
        $this->app->bind(CarveOutRepositoryInterface::class, CarveOutRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(ReportStoreDateRepositoryInterface::class, ReportStoreDateRepository::class);
        $this->app->bind(ReportDeleteRepositoryInterface::class, ReportDeleteRepository::class);
    }

    public function boot()
    {
        //
    }
}
