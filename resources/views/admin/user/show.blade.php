@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Show User</li>
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
            <form class="form dashboard-form">
                @csrf
                <div class="form-heading">
                    <h5>Add User Primary Information</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Full Name</label>
                        <input type="text" readonly value="{{ $admin->user->name }}" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Email</label>
                        <input type="email" readonly name="email" value="{{ $admin->user->email }}"
                            placeholder="Enter Email" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Primary Contact Phone</label>
                        <input type="number" readonly name="phone_number" value="{{ $admin->phone_number }}"
                            placeholder="Enter Contact Phone" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Address</label>
                        <input type="text" readonly name="address" value="{{ $admin->address }}" required
                            placeholder="Enter Owner's Address">
                    </div>
                    <div class="form-group col-md-4">
                        <label>User Role</label>
                        @if (!empty($admin->user->getRoleNames()))
                            @foreach ($admin->user->getRoleNames() as $role)
                                <input type="text" readonly name="address" value="{{ $role }}" required
                                    placeholder="Enter Owner Phone Number">
                            @endforeach
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>
@stop
