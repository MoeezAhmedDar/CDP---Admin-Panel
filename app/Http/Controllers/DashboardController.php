<?php

namespace App\Http\Controllers;

use App\Interfaces\DashboardRepositoryInterface;
use App\Jobs\testJob;
use App\Models\Activity;
use App\Models\AlbertaProvincialCatalog;
use App\Models\BritishColumbiaProvincialCatalog;
use App\Models\CleanSheet;
use App\Models\CovaDaignosticReportRetailer;
use App\Models\CovaDiagnosticReport;
use App\Models\CovaSalesSummaryReport;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Auth;
use App\Models\Lp;
use App\Models\LpVariableFeeStructure;
use App\Models\MbllProvincialCatalog;
use App\Models\OcsProvincialCatalog;
use App\Models\Retailer;
use App\Models\RetailerAddress;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Models\SaskatchewanProvincialCatalog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }
    public function dashboard()
    {
        if (Auth::user()->hasRole('Retailer')) {
            return redirect()->route('reports.monthly.status');
        } elseif (Auth::user()->hasRole('Lp')) {
            return redirect()->route('lp.dashboard');
        } else {
            $dashboard  = $this->dashboardRepository->dashboard();

            return view('dashboard', [
                'lps' => $dashboard['lps'],
                'lpsCount' => $dashboard['lpsCount'],
                'retailers' => $dashboard['retailers'],
                'retailersCount' => $dashboard['retailersCount'],
                'activites' => $dashboard['activites'],
            ]);
        }
    }

    public function retailerDashboard()
    {
        if (Auth::user()->hasRole('Retailer')) {
            abort(404);
        } else {
            abort(404);
        }
    }

    public function lpDashboard()
    {
        if (Auth::user()->hasRole('Lp')) {
            return view('lpDashboard');
        } else {
            abort(404);
        }
    }
}
