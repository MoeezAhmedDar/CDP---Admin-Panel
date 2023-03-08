@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
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
            <form id="user-form" method="post" action="{{ route('admins.update', [$admin->id]) }}"
                class="form dashboard-form">
                @csrf
                @method('put')
                <div class="form-heading">
                    <h5>Add User Primary Information</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                        <label>Full Name</label>
                        <input type="text" value="{{ old('name', $admin->user->name) }}" name="name"
                            placeholder="Enter Name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" name="email" value="{{ old('email', $admin->user->email) }}"
                            placeholder="Enter Email" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" name="phone_number" value="{{ old('phone_number', $admin->phone_number) }}"
                            placeholder="Enter Contact Phone" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address', $admin->address) }}" required
                            placeholder="Enter Owner's Address">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Assign User Role</label>
                        <select name="role" required>
                            <option disabled selected>--{{ __('Select an Option') }}--</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    @foreach ($userRole as $user_role) @if ($user_role['id'] == $role->id){{ 'selected' }} @endif @endforeach>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn">Edit User</button>
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
