@extends('layouts.app')
@section('content')
    <main id="main">
        <div class="page-title-box">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="page-super-title">Retailers</h1>
                        <div class="flex-align gap-30">
                            <h4 class="page-title">Dashboard</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="">IRCC Data Portal (Retailer)</a></li>
                                <li class="breadcrumb-item active">Home</li>
                            </ol>
                        </div>
                    </div>

                    <div class="col-auto">
                        <div class="page-title-right">
                            <h4 class="page-title">Filter By :</h4>

                            <div class="dropdown">
                                <a class="custom-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-title">Jhon Doe</div>
                                    <i class="fas fa-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu option-sub-menu sale-sub-menu">
                                    <li><a href="#">annually</a></li>
                                    <li><a href="#">Monthly</a></li>
                                    <li><a href="#">Quarterly</a></li>
                                    <li><a href="#">Daily</a></li>
                                    <li><a href="#">Custom</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="card-row" id="progress-carousel">
                    <div class="report-card item progress-card">
                        <div class="card-body">
                            <div class="progress-chart">
                                <div class="chart blue" data-percent="68" data-scale-color="#fff">68%</div>
                            </div>
                            <div class="d-flex flex-column progress-chart-content">
                                <h1 class="card-title">5</h1>
                                <h2 class="card-subtitle">Available Offers</h2>
                            </div>
                        </div>
                    </div>

                    <div class="report-card item progress-card">
                        <div class="card-body">
                            <div class="progress-chart">
                                <div class="chart green" data-percent="62" data-scale-color="#fff">62%</div>
                            </div>
                            <div class="d-flex flex-column progress-chart-content">
                                <h1 class="card-title">$2654</h1>
                                <h2 class="card-subtitle">Projected Sales</h2>
                            </div>
                        </div>
                    </div>

                    <div class="report-card item progress-card">
                        <div class="card-body">
                            <div class="progress-chart">
                                <div class="chart dark-blue" data-percent="48" data-scale-color="#fff">48%</div>
                            </div>
                            <div class="d-flex flex-column progress-chart-content">
                                <h1 class="card-title">$8456</h1>
                                <h2 class="card-subtitle">IRCC Generated Revenue</h2>
                            </div>
                        </div>
                    </div>

                    <div class="report-card item progress-card">
                        <div class="card-body">
                            <div class="progress-chart">
                                <div class="chart purple" data-percent="42" data-scale-color="#fff">42%</div>
                            </div>
                            <div class="d-flex flex-column progress-chart-content">
                                <h1 class="card-title">4588</h1>
                                <h2 class="card-subtitle">Gross profit / Margin </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="row content-row">
                    <div class="col-md-6">
                        <div class="content-box">
                            <h4 class="content-title">Sales Report</h4>
                            <div class="img-box">
                                <img src="{{ asset('admin/images/graph-1.png') }}" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="content-box">
                            <h4 class="content-title">Sales by Categories</h4>
                            <div class="img-box">
                                <img src="{{ asset('admin/images/graph-2.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
    <script>
        $(function() {
            $('.chart.blue').easyPieChart({
                barColor: "#6AB6F5",
                size: 125,
                scaleLength: 0,
                lineWidth: 15,
                trackColor: "#EEEEEE",
                lineCap: "circle",
                animate: 2000,
            });
            $('.chart.green').easyPieChart({
                barColor: "#6ED9CB",
                size: 125,
                scaleLength: 0,
                lineWidth: 15,
                trackColor: "#EEEEEE",
                lineCap: "circle",
                animate: 2000,
            });
            $('.chart.dark-blue').easyPieChart({
                barColor: "#6C94FA",
                size: 125,
                scaleLength: 0,
                lineWidth: 15,
                trackColor: "#EEEEEE",
                lineCap: "circle",
                animate: 2000,
            });
            $('.chart.purple').easyPieChart({
                barColor: "#BC6CFA",
                size: 125,
                scaleLength: 0,
                lineWidth: 15,
                trackColor: "#EEEEEE",
                lineCap: "circle",
                animate: 2000,
            });
        });
    </script>
@stop
