<?php

use App\Exports\all;
use App\Exports\allRetailer;
use App\Exports\date;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LpController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\CarveOutController;
use App\Http\Controllers\CleanSheetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReportSubmissonDateController;
use App\Models\Retailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeleteReportController;
use App\Http\Controllers\GreenlineController;
use App\Models\Benefits;
use App\Models\CarveOut;
use App\Models\GreenlineReport;
use App\Models\RetailerReportSubmission;
use Illuminate\Contracts\View;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* Web Routes */

Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/retailer-dashboard', [DashboardController::class, 'retailerDashboard'])->name('retailer.dashboard');
        Route::get('/lp/dashboard', [DashboardController::class, 'lpDashboard'])->name('lp.dashboard');
        Route::get('/monthly-report-by-province', [ReportController::class, 'monthlyReportByProvince'])->name('monthly.report.by.province');
        Route::get('/monthly-report-by-province', [ReportController::class, 'monthlyReportByProvince'])->name('monthly.report.by.province');
        Route::get('/empty-reports-by-retailers', [ReportController::class, 'showEmptyReports'])->name('monthly.report.by.retailers');

        Route::group(['prefix' => 'retailers', 'middleware' => ['permission:retailer-list']], function () {
            Route::get('requested', [RetailerController::class, 'requested'])->name('retailers.requested');
            Route::get('/create', [RetailerController::class, 'create'])->name('retailers.create');
            Route::get('/edit/{retailer}', [RetailerController::class, 'edit'])->name('retailers.edit');
            Route::put('/update/{retailer}', [RetailerController::class, 'update'])->name('retailers.update');
            Route::get('/show/{retailer}', [RetailerController::class, 'show'])->name('retailers.show');
            Route::get('/destroy/{retailer}', [RetailerController::class, 'destroy'])->name('retailers.destroy');
            Route::post('/add', [RetailerController::class, 'add'])->name('retailers.add');
            Route::get('/send-Registration/{user}', [RetailerController::class, 'send_registration'])->name('sendRegistrationAgain');
            Route::get('/edit-requested/{retailer}', [RetailerController::class, 'editRequested'])->name('retailers.edit.requested');
            Route::put('/update-requested/{retailer}', [RetailerController::class, 'update_Requested'])->name('retailers.update.requested');
            Route::get('/send-Email/{user}', [RetailerController::class, 'send_Email'])->name('sendEmailAgain');
            Route::post('/upload-csv', [RetailerController::class, 'uploadCsv'])->name('retailers.upload.csv');
            Route::get('/search/{status}', [RetailerController::class, 'search'])->name('retailers.search');
            Route::get('/address-create/{retailer}', [RetailerController::class, 'address_create'])->name('retailers.address.create');
            Route::get('/address-store/{retailer}', [RetailerController::class, 'address_store'])->name('retailers.address.store');
            Route::get('/destroy-store/{id}', [RetailerController::class, 'destroy_address'])->name('retailers.address.destroy');
            Route::get('/{search?}', [RetailerController::class, 'index'])->name('retailers.index');
            Route::get('reports/{retailer}', [RetailerController::class, 'getRetailerReports'])->name('retailers.reports');
        });

        Route::group(['prefix' => 'lps', 'middleware' => ['permission:lp-list']], function () {
            Route::match(['get', 'post'], '/{search?}', [LpController::class, 'index'])->name('lps.index')->where('search', 'Pending|Approved|Rejected');
            Route::get('/create', [LpController::class, 'create'])->name('lps.create');
            Route::get('/edit/{lp}', [LpController::class, 'edit'])->name('lps.edit');
            Route::get('/destroy/{lp}', [LpController::class, 'destroy'])->name('lps.destroy');
            Route::put('/update/{lp}', [LpController::class, 'update'])->name('lps.update');
            Route::get('/show/{lp}', [LpController::class, 'show'])->name('lps.show');
            Route::get('/offers/{lp}', [LpController::class, 'offers'])->name('lps.offers');
            Route::get('/destroy_offer/{lp}', [LpController::class, 'destroy_offer'])->name('lps.destroy.offers');
            Route::post('/offers-edit/{offer}', [LpController::class, 'update_offer'])->name('lps.update.offers');
            Route::post('/upload/individual/csv', [LpController::class, 'upload_individual_csv'])->name('lp.individualOffers.csv');
            Route::post('/add', [LpController::class, 'add'])->name('lps.add');
            Route::post('/export-lpstatement', [LpController::class, 'exportLpStatement'])->name('lp.statement.export');
        });

        Route::group(['prefix' => 'admins', 'middleware' => ['permission:user-list']], function () {
            Route::get('/', [UserController::class, 'index'])->name('admins.index');
            Route::get('/create', [UserController::class, 'create'])->name('admins.create');
            Route::post('/store', [UserController::class, 'store'])->name('admins.store');
            Route::get('/edit/{admin}', [UserController::class, 'edit'])->name('admins.edit');
            Route::put('/update/{admin}', [UserController::class, 'update'])->name('admins.update');
            Route::get('/show/{admin}', [UserController::class, 'show'])->name('admins.show');
            Route::get('/delete/{admin}', [UserController::class, 'destroy'])->name('admins.destroy');
        });

        Route::group(['prefix' => 'Reports'], function () {
            Route::get('/monthly-status/{status?}', [ReportController::class, 'monthlyReportsStatus'])->name('reports.monthly.status');
            ////
            Route::get('/monthly-search/{status?}', [ReportController::class, 'monthlyReportsSearch'])->name('reports.monthly.search');
            /////
            Route::get('/clean-report/{id}/{retailer_id}', [ReportController::class, 'clean_report'])->name('clean.report')->where('id', '[0-9]+');
            Route::get('/retailer-statement/{id}/{retailer_id}', [ReportController::class, 'retailer_statement'])->name('retailer.statement')->where('id', '[0-9]+');
            Route::get('/lp-statement/{lp}', [ReportController::class, 'lp_statement'])->name('lp.statement')->where('id', '[0-9]+');

            Route::get('/report-submission-date', [ReportSubmissonDateController::class, 'ReportSubmissionDate'])->name('report.submisson.date');
            Route::post('/report-submission-date-store', [ReportSubmissonDateController::class, 'StoreReportSubmissionDate'])->name('store.report.submisson.date');
            Route::post('/report-submission-date-update', [ReportSubmissonDateController::class, 'UpdateReportSubmissionDate'])->name('update.report.submisson.date');
            Route::get('/report-submission-date-delete/{id}', [ReportSubmissonDateController::class, 'DeleteReportSubmissionDate'])->name('delete.report.submisson.date');
        });

        Route::group(['prefix' => 'Delete-Reports'], function () {
            Route::get('/delete-report/{reportSubmission}', [DeleteReportController::class, 'deleteReport'])->name('delete.report');
        });


        Route::group(['prefix' => 'Reports'], function () {
            Route::get('/create/{retailer}', [ReportController::class, 'report_create'])->name('reorts.create');
            Route::post('/store/{retailer}', [ReportController::class, 'report_store'])->name('reports.store');
        });

        Route::group(['prefix' => 'Clean-report'], function () {
            Route::match(['get', 'post'], '/dirty-rows/{id}/{retailer_id}', [CleanSheetController::class, 'dirty_rows'])->name('dirty.rows');
            Route::get('/destroy/{covaReport}', [CleanSheetController::class, 'destroy'])->name('dirty.rows.destroy');
            Route::get('/edit/{covaReport}', [CleanSheetController::class, 'edit'])->name('dirty.rows.edit');
            Route::PUT('/update/{covaReport}', [CleanSheetController::class, 'update'])->name('dirty.rows.update');
        });

        Route::group(['prefix' => 'roles', 'middleware' => ['permission:role-list']], function () {
            Route::get('/index', [RoleController::class, 'index'])->name('roles.index');
            Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
            Route::get('/show/{id}', [RoleController::class, 'show'])->name('roles.show');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
        });

        Route::group(
            ['prefix' => 'retailers'],
            function () {
                Route::get('/edit/profile/{retailer}', [RetailerController::class, 'edit'])->name('retailers.edit.profile');
                Route::put('/update/profile/{retailer}', [RetailerController::class, 'update'])->name('retailers.update.profile');
            }
        );

        Route::group(
            ['prefix' => 'Admin'],
            function () {
                Route::get('/edit-profile/{admin}', [UserController::class, 'edit'])->name('admin.edit.profile');
                Route::put('/update-profile/{admin}', [UserController::class, 'update'])->name('admin.update.profile');
            }
        );

        Route::group(
            ['prefix' => 'carve-outs'],
            function () {
                Route::get('/index/{retailer}', [CarveOutController::class, 'index'])->name('carveout.index');
                Route::post('/store', [CarveOutController::class, 'store'])->name('carveout.store');
                Route::get('/destroy/{carveOut}', [CarveOutController::class, 'destroy'])->name('carveout.destroy');
                Route::post('/edit', [CarveOutController::class, 'edit'])->name('carveout.edit');
            }
        );

        Route::get('/sample-files', function () {
            return view('sample.sample-files');
        })->name('sample.files');
    }
);

Route::get('/get-cities', [UserController::class, 'getCities'])->name('cities.get');

Route::group(['prefix' => 'lp'], function () {
    Route::post('/variable-fee-store/{lp}', [LpController::class, 'variable_fee_store'])->name('lps.variable.fee.store');
    Route::post('/store/{lp}', [LpController::class, 'store'])->name('lps.store');
});

Route::group(['prefix' => 'retailers'], function () {
    Route::get('/form/{user}', [RetailerController::class, 'getRetailerForm'])->name('retailers.get-form');
    Route::get('/store/{retailer}', [RetailerController::class, 'store'])->name('retailers.store');
    Route::get('/success-message', [LpController::class, 'successMessage'])->name('lps.success-message');
});

Route::get('/test', function () {
    return Excel::download(new allRetailer, Carbon::now() . '.xlsx');
});


require __DIR__ . '/auth.php';
