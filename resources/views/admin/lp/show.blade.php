@extends('layouts.app')
@section('content')
    <style>
        textarea {
            resize: none;
            vertical-align: top;
            height: 100px;
            line-height: normal;
        }
    </style>
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">IRCC Data Portal
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Show LP</li>
                    </ol>
                </div>
                <div class="col-auto">
                    <div class="page-title-right">
                        <a href="{{ route('lps.edit', [$lp->id]) }}" class="btn flex-align upload-btn"><i
                                class="icon-upload"></i>Edit LP</a>
                        <a href="{{ route('lps.offers', [$lp->id]) }}" class="btn flex-align upload-btn"><i
                                class="fas fa-edit"></i>Offers</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#individualOffers{{ $lp->user->id }}"
                            class="btn flex-align upload-btn"><i class="fas fa-edit"></i>Add Offers</a>
                        <a href="" class="btn" data-bs-toggle="modal" data-bs-target="#LpStatement">Statement</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <section class="section">
        <div class="container">
            <form action="" class="form dashboard-form">
                <div class="form-heading">
                    <h5>LP Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Lp Legal Name</label>
                        <input type="text" readonly name="name" value="{{ $lp->user->name }}" required
                            placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>LP DBA</label>
                        <input type="text" readonly name="DBA" value="{{ $lp->DBA }}" required
                            placeholder="Enter DBA">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" readonly value="{{ $lp->user->email }}" required placeholder="Enter Email"
                            name="email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Name</label>
                        <input type="text" readonly value="{{ $lp->primary_contact_name }}" required
                            placeholder="Enter Name" name="primary_contact_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Position</label>
                        <input type="text" readonly value="{{ $lp->primary_contact_position }}" required
                            placeholder="Enter Position" name="primary_contact_position">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" readonly value="{{ $lp->primary_contact_phone }}" required
                            placeholder="Enter Contact Phone" name="primary_contact_phone">
                    </div>
                </div>
                <div class="form-heading">
                    <h5>Address</h5>
                </div>
                <div class="row mb-5">
                    <div id="addresses" class="col-md-12 row">
                        <div class="form-group col-md-4">
                            <label class="mb-2">Street Number</label>
                            <input type="number" readonly placeholder="Enter Street Number" required name="street_number"
                                value="{{ $lp->LpAddresses->street_number }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Street Name</label>
                            <input type="text" readonly placeholder="Enter Street Name" required name="street_name"
                                value="{{ $lp->LpAddresses->street_name }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Postal Code</label>
                            <input type="text" readonly required placeholder="Enter Postal Code"
                                value="{{ $lp->LpAddresses->postal_code }}" name="postal_code">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Province</label>
                            <input type="text" readonly required placeholder="Enter Postal Code"
                                value="{{ $lp->LpAddresses->province }}" name="postal_code">
                        </div>
                        <div class="form-group col-md-4">
                            <label>City</label>
                            <input type="text" readonly required value="{{ $lp->LpAddresses->city }}" name="city">
                        </div>
                    </div>
                </div>
                {{--
                @if ($lp->LpFixedFees->count() > 0)
                    @foreach ($lp->LpFixedFees as $lpFee)
                        <div class="form-heading"
                            @if ($lpFee->flag == 1) style="background-color:red !important" @endif>
                            <h5>Fixed Fee Structure</h5>
                        </div>
                        <div id="feeFixed">
                            <div id="onlyFixedFee">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <input type="text" name="" value="{{ $lpFee->province_id }}" readonly
                                            required placeholder="Enter Product Description & Size">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Description & Size</label>
                                        <input type="text" name="product_description_and_size[]"
                                            value="{{ $lpFee->product_description_and_size }}" readonly required
                                            placeholder="Enter Product Description & Size">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">pre Roll</label>
                                        <input type="text" name="pre_roll[]" readonly value="{{ $lpFee->pre_roll }}"
                                            required placeholder="Enter Pre Roll">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Brand</label>
                                        <input type="text" name="brand[]" readonly value="{{ $lpFee->brand }}"
                                            required placeholder="Enter Brand">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial Sku</label>
                                        <input type="text" name="provincial_sku[]"
                                            value="{{ $lpFee->provincial_sku }}" readonly required
                                            placeholder="Enter Provincial Sku">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTIN</label>
                                        <input type="text" name="gtin[]" readonly value="{{ $lpFee->gtin }}"
                                            required placeholder="Enter GTIN">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data Fee</label>
                                        <input type="text" name="data_fee[]" readonly value="{{ $lpFee->data_fee }}"
                                            required placeholder="Enter Data Fee">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cost</label>
                                        <input type="text" name="cost[]" readonly value="{{ $lpFee->cost }}"
                                            required placeholder="Enter Cost">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Comment</label>
                                        <textarea type="text" name="comment[]" cols="40" rows="10" readonly>{{ $lpFee->comment }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if ($lp->LpVariableFees->count() > 0)
                    @foreach ($lp->LpVariableFees as $lpFee)
                        <div class="form-heading"
                            @if ($lpFee->flag == 1) style="background-color:red !important" @endif>
                            <h5>Variable Fee Structure</h5>
                        </div>
                        <div id="onlyVariableFee">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Province</label>
                                    <input type="text" name="brand[]" readonly value="{{ $lpFee->province }}"
                                        required placeholder="Glow Day/Night Cream">

                                </div>
                                <div class="form-group col-md-4">
                                    <label>Category</label>
                                    <input type="text" name="brand[]" readonly value="{{ $lpFee->category }}"
                                        required placeholder="Glow Day/Night Cream">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Brand</label>
                                    <input type="text" name="brand[]" readonly value="{{ $lpFee->brand }}" required
                                        placeholder="Glow Day/Night Cream">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Product Name</label>
                                    <input type="text" name="product_name[]" readonly
                                        value=" {{ $lpFee->product_name }} " required placeholder="Glow Day/Night Cream">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Provincial</label>
                                    <input type="text" name="provincial[]" readonly value="{{ $lpFee->provincial }}"
                                        required placeholder="340076">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>GTin</label>
                                    <input type="text" name="GTin[]" readonly value="{{ $lpFee->GTin }}" required
                                        placeholder="2*60g">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>product</label>
                                    <input type="text" name="product[]" readonly value="{{ $lpFee->product }}"
                                        required placeholder="500">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>THC</label>
                                    <input type="text" name="thc[]" readonly value="{{ $lpFee->thc }}" required
                                        placeholder="6">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>CBD</label>
                                    <input type="text" name="cbd[]" readonly value="{{ $lpFee->cbd }}" required
                                        placeholder="141.3">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Case</label>
                                    <input type="text" name="case[]" readonly value="{{ $lpFee->case }}" required
                                        placeholder="23.55">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>unit_cost</label>
                                    <input type="text" name="unit_cost[]" readonly value="{{ $lpFee->unit_cost }}"
                                        required placeholder="23.55">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>offer</label>
                                    <input type="date" class="input-date" readonly value="{{ $lpFee->offer }}"
                                        name="offer[]" required placeholder="23.55">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Offer End</label>
                                    <input type="date" class="input-date" readonly value="{{ $lpFee->offer_end }}"
                                        name="offer_end[]" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Data</label>
                                    <input type="text" name="data[]" readonly value="{{ $lpFee->data }}" required
                                        placeholder="$3.5/unit">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Comments</label>
                                    <input type="text" name="comments[]" readonly value="{{ $lpFee->comments }}"
                                        required placeholder="$3.5/unit">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Links</label>
                                    <input type="text" name="links[]" readonly value="{{ $lpFee->links }}" required
                                        placeholder="$3.5/unit">
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Comment</label>
                                    <textarea type="text" name="comment[]" cols="40" rows="10" readonly>{{ $lpFee->comment }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif --}}
            </form>
        </div>
    </section>
    <div class="modal fade" id="individualOffers{{ $lp->user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <div class="modal-heading">
                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i> Upload Offers</h4>
                    </div>
                </div>
                <form action="{{ route('lp.individualOffers.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="lp_id" id="" value="{{ $lp->id }}">
                        <div class="form-group">
                            <label for="">Varaiable Fee</label>
                            <input type="file" class="form-control" name="VariableFee" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save
                            changes</button>
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="LpStatement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <div class="modal-heading gap-2">
                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i> LP Statement</h4>

                    </div>
                </div>
                <div class="modal-body">
                    <form id="report-form" action="{{ route('lp.statement.export') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" value="{{ $lp->id }}" name="lp_id">
                        <input type="text" placeholder="Select Month" onfocus="(this.type='month')" name="month"
                            max="{{ date('Y-m') }}" class="form-control" name="date_search"
                            value="{{ \Request::get('date_search') }}" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn">Download</button>
                    <a href="" class="btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                </div>
                </form>
            </div>
        </div>
    </div>
@stop
