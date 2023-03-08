@extends('layouts.app')
@section('content')
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
                    <a href="{{ route('retailers.index') }}" class="btn">Back</a>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form id="lp-form" class="form dashboard-form"
                action="{{ route('retailers.update.requested', [$retailer->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-heading">
                    <h5>Retailer Info</h5>
                </div>
                <div class="row mb-5">
                    <input type="hidden" name="retailer_id" id="{{ $retailer->id }}">
                    <div class="form-group col-md-4">
                        <label>Owner Full Name</label>
                        <input type="text" name="owner_name" value="{{ old('owner_name', $retailer->user->name) }}"
                            required placeholder="Enter Owner Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" value="{{ old('email', $retailer->user->email) }}" required
                            placeholder="Enter Email" name="email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owners Phone Number</label>
                        <input type="number" value="{{ old('owner_phone_number', $retailer->owner_phone_number) }}"
                            required placeholder="Enter Owner Phone Number" name="owner_phone_number">
                    </div>
                    <div class="form-group ">
                        <button type="submit" class="btn">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $('.error').hide();
        $(document).ready(function(e) {

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
