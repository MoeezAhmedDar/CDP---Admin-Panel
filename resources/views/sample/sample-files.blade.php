@extends('../layouts.app')
@section('content')
    <main id="main">
        <div class="page-title-box">
            <div class="container">
                <div class="row">
                    <div class="col flex-align gap-30">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                            <li class="breadcrumb-item active">Sample Files</li>
                        </ol>
                    </div>

                </div>
            </div>

            <section class="section">
                <div class="container">
                    <div class="content-box listing-box">
                        <h4 class="content-title">Sample Files</h4>
                        <div class="table-responsive pb-3">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>POS</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Cova</td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Cova-Diagnostic-Report.xlsx') }}" download>
                                                Diagnostic Report
                                            </a></td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Cova-Sales-Summary.xlsx') }}" download>
                                                Sales Summary Report
                                            </a></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Dutchie</td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Ductie-Inventory-Receipt.xlsx') }}"
                                                download>
                                                Inventory Receipt
                                            </a></td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Ductie-Roll-Forawrd.xlsx') }}" download>
                                                Roll Forawrd Receipt
                                            </a></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Global Till</td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Globat-Till-Diagnostic.xlsx') }}" download>
                                                Diagnostic Report
                                            </a></td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Global-Till-Federal.xlsx') }}" download>
                                                Federal Report
                                            </a></td>

                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>4.</td>
                                        <td>Greenline</td>
                                        <td><a class="btn btn-primary" href="{{ @asset('Sample-Reports/Greenline.xlsx') }}"
                                                download>
                                                Greenline
                                            </a></td>
                                        <td></td>

                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td>Barnet</td>
                                        <td><a class="btn btn-primary" href="{{ @asset('Sample-Reports/Barnet.xlsx') }}"
                                                download>
                                                Barnet
                                            </a></td>
                                        <td></td>

                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>6.</td>
                                        <td>Profit Tech</td>
                                        <td><a class="btn btn-primary" href="{{ @asset('Sample-Reports/ProfitTech.xlsx') }}"
                                                download>
                                                ProfitTech
                                            </a></td>
                                        <td></td>

                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>Techpos</td>
                                        <td><a class="btn btn-primary" href="{{ @asset('Sample-Reports/Techpos.csv') }}"
                                                download>
                                                Techpos
                                            </a></td>
                                        <td></td>

                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td>8.</td>
                                        <td>Ideal</td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/ideal-finalised.xlsx') }}" download>
                                                    Finalised Report
                                            </a></td>
                                        <td><a class="btn btn-primary"
                                                href="{{ @asset('Sample-Reports/Ideal-stock-purchase.xlsx') }}" download>
                                                    Stock Purchase Report
                                            </a></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>9.</td>
                                        <td>Other Pos</td>
                                        <td><a class="btn btn-primary" href="{{ @asset('Sample-Reports/Other-Pos.xlsx') }}"
                                                download>
                                                Other Pos
                                            </a></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
    </main>
@stop
