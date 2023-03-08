@extends('auth.layouts.layout')
@section('content')
    {{-- hewo@mailinator.com --}}
    <div id="wrapper">
        <div class="signup forget-password bg-lightest-gray">
            <div class="login-form">
                <div class="text-center">
                    <div class="logo">
                        <a href="">
                            <img src="{{ asset('admin/images/logo.png') }}" alt="">
                        </a>
                    </div>
                    <h2 class="text-title text-primary">
                        New Password
                    </h2>
                </div>
                <form method="POST" id="reset-password-form" action="{{ route('password.update') }}">
                    @csrf
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group">
                        <label for="username">Email</label>
                        <input type="text" class="form-control" type="email" name="email" value="{{ old('email') }}"
                            required autofocus id="email" placeholder="Enter Email">
                    </div>
                    <div class="form-group">

                        <label>New Password</label>
                        <div class="password-field">
                            <input type="password" required class="form-control" name="password"
                                placeholder="Enter Your Password">
                            <span class="showpassword"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="password-field">
                            <input type="password" required class="form-control" name="password_confirmation"
                                placeholder="Confirm Password">
                            <span class="showpassword"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="" class="btn btn-gray">Back</a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
