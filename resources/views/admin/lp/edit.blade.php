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
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Edit LP</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form id="lp-form" class="form dashboard-form" action="{{ route('lps.update', [$lp->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-heading">
                    <h5>LP Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <input type="hidden" name="lp_id" value="{{ $lp->id }}">
                        <label>Lp Legal Name</label>
                        <input type="text" name="name" value="{{ old('name', $lp->user->name) }}" required
                            placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>LP DBA</label>
                        <input type="text" name="DBA" value="{{ old('DBA', $lp->DBA) }}" required
                            placeholder="Enter DBA" class="form-control input-lg">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" name="email" value="{{ old('email', $lp->user->email) }}" required
                            placeholder="Enter Email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Name</label>
                        <input type="text" name="primary_contact_name"
                            value="{{ old('primary_contact_name', $lp->primary_contact_name) }}" required
                            placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Position</label>
                        <input type="text" name="primary_contact_position"
                            value="{{ old('primary_contact_position', $lp->primary_contact_position) }}" required
                            placeholder="Enter Position">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" name="primary_contact_phone"
                            value="{{ old('primary_contact_phone', $lp->primary_contact_phone) }}"
                            placeholder="Enter Contact Phone">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <select class="select2 form-control mb-3 custom-select" required name="status"
                            style="width: 100%; height:36px;">
                            @foreach ($statuses as $status)
                                <option {{ $status == $lp->status ? 'selected' : '' }} value="{{ $status }}">
                                    {{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-heading">
                    <h5>Address</h5>
                </div>

                <div class="row mb-5">
                    <div id="addresses" class="col-md-12 row">
                        <div class="form-group col-md-4">
                            <label class="mb-2">Street Number</label>
                            <input type="number" name="street_number" placeholder="Enter Street Number" required
                                value="{{ old('street_number', $lp->LpAddresses->street_number) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Street Name</label>
                            <input type="text" placeholder="Enter Street Name" required name="street_name"
                                value="{{ old('street_name', $lp->LpAddresses->street_name) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Postal Code</label>
                            <input type="text" name="postal_code" placeholder="Enter Postal Code"
                                value="{{ old('postal_code', $lp->LpAddresses->postal_code) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Province</label>
                            <select class="select2 form-control mb-3 custom-select" id="province" required name="province"
                                style="width: 100%; height:36px;">
                                <option value="{{ old('province', $lp->LpAddresses->province) }}">
                                    {{ old('province', $lp->LpAddresses->province) }}</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>City</label>
                            <select class="select2 form-control mb-3 custom-select" id="city" required
                                style="width: 100%; height:36px;" name="city">
                                <option value="{{ old('city', $lp->LpAddresses->city) }}">
                                    {{ old('city', $lp->LpAddresses->city) }}
                                </option>
                            </select>
                        </div>
                    </div>

                </div>


                <div class="form-group ">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function(e) {
            $(document).on("change", "#province", function(e) {
                let province = $("#province").find(":selected").val();

                $.ajax({
                    url: "{{ route('cities.get') }}",
                    type: "GET",
                    data: {
                        'province': province,
                    },
                    success: function(response) {
                        $("#city").empty();
                        $("#city").append(`<option value="">Select City Name</option>`);
                        $.each(response, function(key, value) {
                            $('#city').append(`<option value="` + value
                                .city + `">` + value.city + `</option>`);
                        });
                    },
                    error: function(reject) {
                        console.log(reject);
                    },
                });
            });

            var createLpForm = $("#lp-form");

            createLpForm.validate({
                rules: {
                    email: {
                        email: true,
                    },

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

        });
    </script>
@stop
