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
                            <li class="breadcrumb-item active">Carve Outs</li>
                        </ol>
                    </div>

                    <div class="col-auto">
                        <div class="page-title-right">
                            <a href="" class="btn" data-bs-toggle="modal" data-bs-target="#uploadcarveout">+ Add
                                Carve Out</a>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <section class="section">
                <div class="container">
                    <div class="content-box listing-box">
                        <h4 class="content-title">Carve Outs</h4>
                        <div class="table-responsive pb-3">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Retailer Name</th>
                                        <th>Email</th>
                                        <th>Carve Out</th>
                                        <th>Location</th>
                                        <th>Lisence Producer</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($carveouts as $carveout)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $carveout->retailer->user->name }}</td>
                                            <td>{{ $carveout->retailer->user->email }}</td>
                                            <td>{{ $carveout->carve_outs }}</td>
                                            <td>{{ $carveout->location }}</td>
                                            <td>{{ $carveout->lp }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <ul class="action-list">
                                                        <li><a href="" data-bs-toggle="modal"
                                                                data-bs-target="#editcarveout{{ $carveout->id }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </li>
                                                        <li><a href="{{ route('carveout.destroy', [$carveout->id]) }}"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="editcarveout{{ $carveout->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header p-4">
                                                        <div class="modal-heading gap-2">
                                                            <h4 class="modal-title flex-align gap-2"><i
                                                                    class="icon-upload"></i> Edit Carve Out</h4>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="report-form" action="{{ route('carveout.edit') }}"
                                                            method="POST" novalidate>
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $carveout->id }}">
                                                            <div class="dropdown mb-2">
                                                                <select name="location" id="locations"
                                                                    class="form-select border-primary " required>
                                                                    <option value="">Location</option>
                                                                    @foreach ($provinces as $province)
                                                                        <option value="{{ $province->province_id }}">
                                                                            {{ $province->province_id }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="dropdown">
                                                                <select id="lps" class="form-select border-primary "
                                                                    name="lp" required>
                                                                    <option class="other" value="">Lisence Provider
                                                                    </option>
                                                                    @foreach ($lps as $lp)
                                                                        <option class="other" value="{{ $lp->DBA }}">
                                                                            {{ $lp->DBA }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" id="submit"
                                                            class="btn btn-primary">Add</button>
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
                        {!! $carveouts->links() !!}
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="uploadcarveout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <div class="modal-heading gap-2">
                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i> Add Carve Out</h4>

                    </div>
                </div>
                <div class="modal-body">
                    <form id="report-form" action="{{ route('carveout.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="dropdown mb-3">
                            <input type="hidden" name="id" value="{{ $retailer->id }}">
                            <label>Location*</label>
                            <select name="location" required id="locations" class="form-select border-primary" required>
                                <option value="">Location</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->province_id }}">{{ $province->province_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="dropdown">
                            <label>Lisence Provider*</label>
                            <select id="lps" name="lp" required class="form-select border-primary" required>
                                <option class="other" value="">Lisence Provider</option>
                                @foreach ($lps as $lp)
                                    @if ($lp->user)
                                        <option class="other" value="{{ $lp->user->name }}">{{ $lp->user->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
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
