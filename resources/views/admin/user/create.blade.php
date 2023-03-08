@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Add User</li>
                    </ol>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admins.index') }}" class="btn">Back</a>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form id="user-form" method="POST" action="{{ route('admins.store') }}" class="form dashboard-form">
                @csrf
                <div class="form-heading">
                    <h5>Add User Primary Information</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Full Name</label>
                        <input type="text" value="{{ old('name') }}" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" name="phone_number" value="{{ old('phone_number') }}"
                            placeholder="Enter Contact Phone" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" required
                            placeholder="Enter Owner's Address">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Assign User Role</label>
                        <select name="role" required>
                            <option disabled selected>--{{ __('Select an Option') }}--</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"> {{ $role->name }} </option>
                            @endforeach
                            {{-- <a href="{{ route('roles.create') }}">
                                <option class="fw-bold">+ Add New Role
                                </option>
                            </a> --}}
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn">Add User</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function(e) {
            var createUserForm = $("#user-form");

            createUserForm.validate({
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
