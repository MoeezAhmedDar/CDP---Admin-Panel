@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Add Retailer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form id="retailer-form" class="form dashboard-form" action="{{ route('retailers.add') }}" method="POST"
                onsubmit="return validate()">
                @csrf
                <div class="form-heading">
                    <h5>Retailer Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Owner First name<span style="color:red;">*</span></label>
                        <input type="text" value="{{ old('owner_first_name') }}" name="owner_first_name" required
                            placeholder="Enter Owner first Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owner Last name<span style="color:red;">*</span></label>
                        <input type="text" value="{{ old('owner_last_name') }}" name="owner_last_name" required
                            placeholder="Enter Owner last Name">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Email<span style="color:red;">*</span></label>
                        <input type="email" value="{{ old('email') }}" required placeholder="Enter Email" name="email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owners Phone Number<span style="color:red;">*</span></label>
                        <input type="number" value="{{ old('owner_phone_number') }}" required
                            placeholder="Enter Owner Phone Number" name="owner_phone_number">
                    </div>
                    <div class="form-group ">
                        <button type="submit" class="btn">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function(e) {
            var createRetailerForm = $("#retailer-form");

            createRetailerForm.validate({
                rules: {
                    'street_number[]': {
                        required: true,
                    },
                    'street_name[]': {
                        required: true,
                    },
                    'postal_code[]': {
                        required: true,
                    },
                    'province[]': {
                        required: true,
                    },
                    'city[]': {
                        required: true,
                    },
                    'location[]': {
                        required: true,
                    },
                    'contact_person_name_at_location[]': {
                        required: true,
                    },
                    'contact_person_phone_number_at_location[]': {
                        required: true,
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@stop
