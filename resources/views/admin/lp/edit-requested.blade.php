@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">LP Retailer</li>
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
            <form id="lp-form" class="form dashboard-form" action="{{ route('lps.update.requested', [$lp->id]) }}"
                method="POST">
                @csrf
                @method('put')
                <div class="form-heading">
                    <h5>LP Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Lp Legal Name</label>
                        <input type="text" name="name" value="{{ old('name', $lp->user->name) }}" required
                            placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" value="{{ old('email', $lp->user->email) }}" required
                            placeholder="Enter Email" name="email">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" value="{{ old('primary_contact_phone', $lp->primary_contact_phone) }}"
                            required placeholder="Enter Contact Phone" name="primary_contact_phone">
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
