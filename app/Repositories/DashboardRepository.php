<?php

namespace App\Repositories;

use App\Interfaces\DashboardRepositoryInterface;
use App\Models\Activity;
use App\Models\Lp;
use App\Models\Retailer;

class DashboardRepository implements DashboardRepositoryInterface
{

    public function dashboard()
    {
        $lps = Lp::where('status', 'Approved')->latest()->with(['user', 'LpAddresses'])->paginate(5);
        $lpsCount = Lp::count();

        $retailers = Retailer::where('status', 'Approved')->with(['user', 'RetailerAddresses', 'ReportStatus' => function ($q) {
            return $q->where('status', 'Submited')->where('pos', 'cova')->orderBy('id', 'DESC');
        }])
            ->whereHas('ReportStatus', function ($q) {
                return $q->where('status', 'Submited')->where('pos', 'cova')->orderBy('id', 'DESC');
            })
            ->latest()->paginate(5);
        $retailersCount = Retailer::count();
        $activites = Activity::latest()->paginate(5);

        return [
            'lps' => $lps,
            'lpsCount' => $lpsCount,
            'retailers' => $retailers,
            'retailersCount' => $retailersCount,
            'activites' => $activites
        ];
    }
}
