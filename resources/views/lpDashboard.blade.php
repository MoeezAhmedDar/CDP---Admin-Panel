@extends('layouts.app')
@section('content')
    <main id="main">
        <div class="page-title-box">
            <div class="container">
                <div class="row">
                    <div class="col flex-align gap-30">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
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
                <div class="card-row" id="report-carousel">
                    <div class="report-card item">
                        <div class="card-body">
                            <h1 class="card-title bg-skyblue">5</h1>
                            <h2 class="card-subtitle">Retailer</h2>
                        </div>
                    </div>

                    <div class="report-card item">
                        <div class="card-body">
                            <h1 class="card-title bg-green">6</h1>
                            <h2 class="card-subtitle">Licence Provider</h2>
                        </div>
                    </div>

                    <div class="report-card item">
                        <div class="card-body">
                            <h1 class="card-title bg-blue">200</h1>
                            <h2 class="card-subtitle">Reports Generated</h2>
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
                            <h4 class="content-title">Revenue</h4>
                            <div class="img-box">
                                <img src="{{ asset('admin/images/graph-2.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row content-row">

                    <div class="col-md-6">
                        <div class="content-box">
                            <h4 class="content-title">Area Sales</h4>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="ímg-box">
                                        <img src="{{ asset('admin/images/graph-3.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h6 class="text-capitalize mb-4 text-primary fw-500">Last Month Revenue</h6>
                                    <div class="text-box py-3 border-top-gray">
                                        <div class="space-between mb-2">
                                            <div class="fw-500">Ontario</div>
                                            <div class="fw-500 text-black flex-align gap-2">2.52% <i
                                                    class="far fa-arrow-up text-green"></i></div>
                                        </div>
                                        <h4 class="text-mischka-gray mb-0 fw-500">$5643</h4>
                                    </div>
                                    <div class="text-box py-3 border-top-gray">
                                        <div class="space-between mb-2">
                                            <div class="fw-500">Quebec</div>
                                            <div class="fw-500 text-black flex-align gap-2">1.52% <i
                                                    class="far fa-arrow-down text-red"></i></div>
                                        </div>
                                        <h4 class="text-mischka-gray mb-0 fw-500">$120</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="content-box">
                            <h4 class="content-title border-bottom-gray">Recent Activity Feed</h4>

                            <ul class="activity-list">
                                <li>
                                    <div class="fw-bold">Jun 25</div>
                                    <p>Responded to need “Volunteer Activities”</p>
                                </li>
                                <li>
                                    <div class="fw-bold">Jun 26</div>
                                    <p>Joined the group “Boardsmanship Forum”</p>
                                </li>
                                <li>
                                    <div class="fw-bold">Jun 26</div>
                                    <p>Joined the group “Boardsmanship Forum”</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@stop
