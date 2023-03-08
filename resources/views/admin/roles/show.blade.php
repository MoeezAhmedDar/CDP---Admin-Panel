@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Show Role</li>
                    </ol>
                </div>
                <div class="col-auto">
                    <a href="{{ route('roles.index') }}" class="btn">Back</a>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <form method="POST" action=" {{ route('roles.store') }} " class="form dashboard-form">
                @csrf
                <div class="row mb-5">
                    <div class="form-group col-md-8">
                        <label>
                            <h5>Role Name</h5>
                        </label>
                        <input type="text" value="{{ $role->name }}" name="name" readonly>
                    </div>
                    <div class="content-box col-md-12">
                        <h4 class="content-title">Permissions</h4>
                        <div class="row">
                            @foreach ($rolePermissions as $rolePermission)
                                <div class="col-md-3">
                                    <div class="form-check checkbox">
                                        <label>{{ $rolePermission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@stop
