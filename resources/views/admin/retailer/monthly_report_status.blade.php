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
                            <li class="breadcrumb-item active">Retailer</li>
                        </ol>
                    </div>
                    <div class="col-auto">
                        <div class="page-title-right">
                            <div class="dropdown">
                                <a class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="icon-filter"></i>
                                </a>
                                <ul class="dropdown-menu option-sub-menu">
                                    <li><a href="{{ route('retailers.search', ['Pending']) }}"><i
                                                class="icon-edit"></i>Pending</a>
                                    </li>
                                    <li><a href="{{ route('retailers.search', ['Submited']) }}"><i
                                                class="icon-edit"></i>Submitted</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">Retailer Monthly Report Status</h4>
                    <div class="table-responsive pb-3">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Province</th>
                                    <th>Locations</th>
                                    <th>Report Status</th>
                                    <th>Submission Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailers as $retailer)
                                    @foreach ($retailer->ReportStatus as $reportStatus)
                                        <tr>
                                            <td>
                                                <div class="user-img">
                                                    <img src="{{ asset('admin/images/user-02.png') }}" alt="">
                                                </div>
                                                <div class="user-title">{{ $retailer->user->name }}</div>
                                            </td>
                                            <td> {{ $reportStatus->province }} </td>
                                            <td> {{ $reportStatus->location }} </td>
                                            <td>
                                                <div class="flex-align gap-2">
                                                    {{ $reportStatus->status }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex-align gap-2">
                                                    {{ $retailer->ReportStatus->first()->date }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $retailers->links() !!}
                </div>

            </div>
        </section>
    </main>
@stop
