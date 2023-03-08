@extends('../layouts.app')
@section('content')
    <main id="main">
        <div class="page-title-box">
            <div class="container">
                <div class="row flex-align">
                    <div class="col">
                        <h1 class="page-super-title">Super Admin</h1>
                        <div class="flex-align gap-30">
                            <h4 class="page-title">Comparison</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                                <li class="breadcrumb-item active">Comparison</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="row content-row justify-content-center">
                    <div class="col-md-5">
                        <div class="content-box">
                            <h4 class="content-title">Retailers</h4>
                            <div class="table-responsive">
                                <table class="table mb-0 comparison-table">
                                    <thead>
                                        <tr>
                                            <th>Province</th>
                                            <th>Total Fee in $</th>
                                            <th>Total Purchase Cost </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Ontario</td>
                                            <td>$
                                                {{ isset($lpReport['ontarioFeeInDollars']) ? number_format($lpReport['ontarioFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['ontarioTotalPurchasedCost']) ? number_format($lpReport['ontarioTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Manitoba</td>
                                            <td>$
                                                {{ isset($lpReport['manitobaFeeInDollars']) ? number_format($lpReport['manitobaFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['manitobaTotalPurchasedCost']) ? number_format($lpReport['manitobaTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Saskatchewan</td>
                                            <td>$
                                                {{ isset($lpReport['saskatchewanFeeInDollars']) ? number_format($lpReport['saskatchewanFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['saskatchewanTotalPurchasedCost']) ? number_format($lpReport['saskatchewanTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Alberta</td>
                                            <td>$
                                                {{ isset($lpReport['albertaFeeInDollars']) ? number_format($lpReport['albertaFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['albertaTotalPurchasedCost']) ? number_format($lpReport['albertaTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>British Columbia</td>
                                            <td>$
                                                {{ isset($lpReport['britishcolumbiaFeeInDollars']) ? number_format($lpReport['britishcolumbiaFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['britishcolumbiaTotalPurchasedCost']) ? number_format($lpReport['britishcolumbiaTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Total</td>
                                            <td>$
                                                {{ isset($lpReport['SumOfFeeInDollars']) ? number_format($lpReport['SumOfFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['SumOfTotalPurchasedCost']) ? number_format($lpReport['SumOfTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CarveOuts Total</td>
                                            <td>$
                                                {{ isset($lpReport['SumOfFeeInDollars']) ? number_format($retailerReport['FeeInDollars'] - $lpReport['SumOfFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['SumOfTotalPurchasedCost']) ? number_format($retailerReport['TotalPurchasedCost'] - $lpReport['SumOfTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>No Of Retailers(Locations) Submitted their Report</td>
                                            <td>{{ isset($retailerReport['CountOfRetailer']) ? $retailerReport['CountOfRetailer'] : '0' }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 align-self-center text-center mb-3 mb-md-0">
                        VS
                    </div>
                    <div class="col-md-5">
                        <div class="content-box">
                            <h4 class="content-title">LP</h4>
                            <div class="table-responsive">
                                <table class="table mb-0 comparison-table">
                                    <thead>
                                        <tr>
                                            <th>Province</th>
                                            <th>Total Fee in $</th>
                                            <th>Total Purchase Cost </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>Ontario</td>
                                            <td>$
                                                {{ isset($lpReport['ontarioFeeInDollars']) ? number_format($lpReport['ontarioFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['ontarioTotalPurchasedCost']) ? number_format($lpReport['ontarioTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Manitoba</td>
                                            <td>$
                                                {{ isset($lpReport['manitobaFeeInDollars']) ? number_format($lpReport['manitobaFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['manitobaTotalPurchasedCost']) ? number_format($lpReport['manitobaTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Saskatchewan</td>
                                            <td>$
                                                {{ isset($lpReport['saskatchewanFeeInDollars']) ? number_format($lpReport['saskatchewanFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['saskatchewanTotalPurchasedCost']) ? number_format($lpReport['saskatchewanTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Alberta</td>
                                            <td>$
                                                {{ isset($lpReport['albertaFeeInDollars']) ? number_format($lpReport['albertaFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['albertaTotalPurchasedCost']) ? number_format($lpReport['albertaTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>British Columbia</td>
                                            <td>$
                                                {{ isset($lpReport['britishcolumbiaFeeInDollars']) ? number_format($lpReport['britishcolumbiaFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['britishcolumbiaTotalPurchasedCost']) ? number_format($lpReport['britishcolumbiaTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Total</td>
                                            <td>$
                                                {{ isset($lpReport['SumOfFeeInDollars']) ? number_format($lpReport['SumOfFeeInDollars'], 2) : '0.00' }}
                                            </td>
                                            <td>$
                                                {{ isset($lpReport['SumOfTotalPurchasedCost']) ? number_format($lpReport['SumOfTotalPurchasedCost'], 2) : '0.00' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><a href="{{ route('monthly.report.by.retailers') }}">No Of
                                                    Retailers(Locations)
                                                    Showing on LP Statement</a>
                                            </td>
                                            <td>{{ isset($lpReport['CountOfRetailer']) ? $lpReport['CountOfRetailer'] : '0' }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@stop
