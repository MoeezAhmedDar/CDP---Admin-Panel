@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Add LP</li>
                    </ol>
                </div>
                <div class="col-auto">
                    <a href="{{ route('lps.index') }}" class="btn">Back</a>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form id="lp-form" class="form dashboard-form" action="{{ route('lps.add') }}" method="POST">
                @csrf
                <div class="form-heading">
                    <h5>LP Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>LP Legal Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" value="{{ old('email') }}" required placeholder="Enter Email" name="email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" value="{{ old('primary_contact_phone') }}" required
                            placeholder="Enter Contact Phone" name="primary_contact_phone">
                    </div>
                    <div class="form-group col-md-4">
                        <label>LP DBA</label>
                        <input type="text" name="DBA" value="{{ old('DBA') }}" required placeholder="Enter DBA">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Name</label>
                        <input type="text" value="{{ old('primary_contact_name') }}" required placeholder="Enter Name"
                            name="primary_contact_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Position</label>
                        <input type="text" value="{{ old('primary_contact_position') }}" required
                            placeholder="Enter Position" name="primary_contact_position">
                    </div>
                </div>

                <div class="form-heading">
                    <h5>Address</h5>
                </div>

                <div class="row mb-5">
                    <div id="addresses" class="col-md-12 row">
                        <div class="form-group col-md-4">
                            <label class="mb-2">Street Number</label>
                            <input type="number" placeholder="Enter Street Number" required name="street_number"
                                value="{{ old('street_number') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Street Name</label>
                            <input type="text" placeholder="Enter Street Name" required name="street_name"
                                value="{{ old('street_name') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Postal Code</label>
                            <input type="text" required placeholder="Enter Postal Code" value="{{ old('postal_code') }}"
                                name="postal_code">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Province</label>
                            <select class="select2 form-control mb-3 custom-select" id="province" required name="province"
                                style="width: 100%; height:36px;">
                                <option value="{{ old('province') }}">
                                    {{ old('province', 'Select Province Name') }}</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label id="province-error" class="error" for="province">This field is required.</label>
                        </div>
                        <div class="form-group col-md-4">
                            <label>City</label>
                            <select class="select2 form-control mb-3 custom-select" id="city" required
                                style="width: 100%; height:36px;" name="city">
                                <option value="{{ old('city') }}">{{ old('city', 'Select City Name') }}
                                </option>
                            </select>
                            <label id="city-error" class="error" for="city">This field is required.</label>
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
        $('.error').hide();
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
