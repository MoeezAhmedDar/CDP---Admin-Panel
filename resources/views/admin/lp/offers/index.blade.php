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
                            <li class="breadcrumb-item active">Offers</li>
                        </ol>
                    </div>
                    <div class="col-auto">
                        {{-- <div class="page-title-right">
                            <a href="" class="btn" data-bs-toggle="modal" data-bs-target="#uploadcarveout">+ Add</a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <br>
            <section class="section">
                <div class="container">
                    <div class="content-box listing-box">
                        <h4 class="content-title">Offers</h4>
                        <div class="table-responsive pb-3">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Province</th>
                                        <th>Product</th>
                                        <th>Provincial Sku</th>
                                        <th>GTIN</th>
                                        <th>Data Fee</th>
                                        <th>Cost</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($offers as $offer)
                                        <tr
                                            @if ($offer->flag == 1) style="background-color:red !important" @endif>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $offer->province }}</td>
                                            <td>{{ $offer->product_name }}</td>
                                            <td>{{ $offer->provincial }}</td>
                                            <td>{{ $offer->GTin }}</td>
                                            <td>{{ $offer->data * 100 }}%</td>
                                            <td>{{ $offer->unit_cost }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <ul class="action-list">
                                                        <li><a href="" data-bs-toggle="modal"
                                                                data-bs-target="#editcarveout{{ $offer->id }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </li>
                                                        <li><a href="" data-bs-toggle="modal"
                                                                data-bs-target="#deletecarveout{{ $offer->id }}"><i
                                                                    class="fa fa-trash"></i></a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <div class="modal fade" id="editcarveout{{ $offer->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header p-4">
                                                            <div class="modal-heading gap-2">
                                                                <h4 class="modal-title flex-align gap-2"><i
                                                                        class="icon-upload"></i>Edit Offer</h4>
                                                            </div>
                                                        </div>
                                                        <form id="report-form"
                                                            action="{{ route('lps.update.offers', [$offer->id]) }}"
                                                            method="POST" novalidate>
                                                            <div class="modal-body">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label> Product Name </label>
                                                                    <input class="form-control" type="text"
                                                                        name="product_name"
                                                                        value="{{ $offer->product_name }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label> Provincial Sku </label>
                                                                    <input class="form-control" name="provincial"
                                                                        type="text" value="{{ $offer->provincial }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label> GTIN </label>
                                                                    <input class="form-control" type="text"
                                                                        name="GTin" value="{{ $offer->GTin }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label> Cost </label>
                                                                    <input class="form-control" type="text"
                                                                        name="unit_cost" value="{{ $offer->unit_cost }}">
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" id="submit"
                                                                    class="btn btn-primary">Submit</button>
                                                                <a href="" class="btn" data-bs-dismiss="modal"
                                                                    aria-label="Close">Close</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="deletecarveout{{ $offer->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header p-4">
                                                            <div class="modal-heading">
                                                                <h4 class="modal-title flex-align gap-2"><i
                                                                        class="icon-upload"></i>
                                                                    Delete Offer</h4>
                                                            </div>
                                                        </div>

                                                        <div class="modal-body">
                                                            <h3 style="text-align: center">Are you Sure You want to Delete
                                                                Offer</h3>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a class="btn btn-warning"
                                                                href="{{ route('lps.destroy.offers', [$offer->id]) }}">Delete</a>
                                                            <a type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {!! $offers->links() !!}
                    </div>
                </div>
            </section>
        </div>
    </main>
@stop
