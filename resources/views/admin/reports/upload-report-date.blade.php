@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Report Upload Limit</li>
                    </ol>
                </div>
                <div class="col-auto">
                    <div class="page-title-right">
                        <a href="" class="btn" data-bs-toggle="modal" data-bs-target="#uploaddate">+ Add
                            Date</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <div class="content-box listing-box">
                <h4 class="content-title">Report Dates</h4>
                <div class="table-responsive">
                    <table class="table  mb-0" >
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Starting Date</th>
                                <th>Ending Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($date as $date)
                                <tr>
                                    <td>{{ $date->month }}</td>
                                    <td>{{ $date->year }}</td>
                                    <td>{{ Carbon\Carbon::parse($date->starting_date)->format('d F Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($date->ending_date)->format('d F Y') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <ul class="action-list">
                                                <li><a href="" data-bs-toggle="modal"
                                                        data-bs-target="#editdate{{ $date->id }}"><i
                                                            class="fa fa-edit"></i></a>
                                                </li>
                                                <li><a href="{{ route('delete.report.submisson.date', $date->id) }}"><i
                                                            class="fa fa-trash"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editdate{{ $date->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header p-4">
                                                <div class="modal-heading gap-2">
                                                    <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i>
                                                        Select Report Upload Date Range
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <form novalidate action="{{ route('update.report.submisson.date') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="date_id" value="{{ $date->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="dropdown form-group col-md-6">
                                                                <label>Starting Date</label>
                                                                <input type="date" name="starting_date"
                                                                    min="{{ date('Y-m-d') }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="dropdown form-group col-md-6">
                                                                <label>Ending Date</label>
                                                                <input type="date" name="ending_date"
                                                                    min="{{ date('Y-m-d') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" id="submit" class="btn btn-primary">Add</button>
                                                <a href="" class="btn" data-bs-dismiss="modal"
                                                    aria-label="Close">Close</a>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
    <!-- Modal -->
    <div class="modal fade" id="uploaddate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <div class="modal-heading gap-2">
                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i>
                            Select Report Upload Date Range
                        </h4>
                    </div>
                </div>
                <div class="modal-body">
                    <form novalidate action="{{ route('store.report.submisson.date') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="dropdown form-group col-md-6">
                                    <label>Starting Date</label>
                                    <input type="date" name="starting_date" min="{{ date('Y-m-d') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dropdown form-group col-md-6">
                                    <label>Ending Date</label>
                                    <input type="date" name="ending_date" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Add</button>
                    <a href="" class="btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>
@stop
