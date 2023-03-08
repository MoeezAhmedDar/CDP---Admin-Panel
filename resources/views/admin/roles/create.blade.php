@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Add New Role</li>
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
            <form id="role-form" method="POST" action=" {{ route('roles.store') }} " class="form dashboard-form">
                @csrf
                <div class="row mb-5">
                    <div class="form-group col-md-8">
                        <label>
                            <h5>Role Name</h5>
                        </label>
                        <input type="text" required value="{{ old('name') }}" name="name"
                            placeholder="Enter Role Name">
                    </div>
                    <div class="content-box col-md-12">
                        <h4 class="content-title">Permissions</h4>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-3">
                                    <div class="form-check checkbox">
                                        <input class="form-check-input" type="checkbox" name="permission[]"
                                            id="{{ $permission->id }}" value="{{ $permission->id }}">
                                        <label class="form-check-label"
                                            for="{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-12 mt-5">
                                <button type="submit" class="btn">Save New Role</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function(e) {
            var createRoleForm = $("#role-form");

            createRoleForm.validate({
                rules: {
                    name: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
@stop
