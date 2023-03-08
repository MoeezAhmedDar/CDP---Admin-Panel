<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSubmissionDate extends Model
{
    use HasFactory;
    protected $fillable = [
        'starting_date',
        'ending_date',
        'month',
        'year'
    ];
}
