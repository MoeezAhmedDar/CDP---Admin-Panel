<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LpAddress;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retailer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'corporate_name',
        'DBA',
        'owner_phone_number',
        'aggregated_data',
        'status',
        'report_count',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function retailerStatements()
    {
        return $this->hasManyThrough(RetailerStatement::class,RetailerReportSubmission::class,'retailer_id','retailerReportSubmission_id','id','id');
    }

    public function RetailerAddresses()
    {
        return $this->hasMany(RetailerAddress::class, 'retailer_id', 'id');
    }

    public function ReportStatus()
    {
        return $this->hasMany(RetailerReportSubmission::class, 'retailer_id', 'id');
    }

    public function covaDaignosticReports()
    {
        return $this->belongsToMany(CovaDiagnosticReport::class, 'cova_daignostic_report_retailers', 'retailer_id', 'cova_daignostic_id')->withPivot('date');
    }
    public function DuctieDaignosticReports()
    {
        return $this->belongsToMany(DuctieDiagnosticReport::class, 'ductie_diagnostic_report_retailers', 'retailer_id', 'dd_report_id')->withPivot('date');
    }
    public function gobatellDiagnosticReports()
    {
        return $this->belongsToMany(GobatellDiagnosticReport::class, 'gobatell_diagnostic_report_retailers', 'retailer_id', 'gb_diagnostic_id')->withPivot('date');
    }
    public function greenlineReports()
    {
        return $this->belongsToMany(GreenlineReport::class, 'greenline_retailer_reports', 'retailer_id', 'greenline_report_id');
    }

    public function techposReports()
    {
        return $this->belongsToMany(TechPosReport::class, 'tech_pos_retailer_reports', 'retailer_id', 'techpos_report_id');
    }
    public function pennylaneReports()
    {
        return $this->belongsToMany(PennyLaneReport::class, 'penny_lane_retailer_reports', 'retailer_id', 'penny_lane_report_id');
    }
    public function ductieReports()
    {
        return $this->belongsToMany(DuctieReport::class, 'ductie_retailer_reports', 'retailer_id', 'ductie_report_id');
    }
    public function profittechReports()
    {
        return $this->belongsToMany(ProfitTechReport::class, 'profit_tech_retailer_reports', 'retailer_id', 'profit_tech_report_id');
    }

    public function Carveouts()
    {
        return $this->hasMany(CarveOut::class, 'retailer_id', 'id');
    }
}
