@extends('auth.layouts.layout')
@section('content')<div id="wrapper">
        <div class="signup forget-password bg-lightest-gray">
            <div class="login-form">
                <div class="text-center">
                    <div class="logo">
                        <a href="">
                            <img src="{{ asset('admin/images/logo.png') }}" alt="">
                        </a>
                    </div>
                    <h2 class="text-title text-primary">
                        Enter Your Email
                    </h2>
                </div>
                <form class="form" method="POST" id="forget-password-form" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-control"
                            placeholder="Enter Your Email">
                    </div>
                    <div class="form-group">
                        <a href="/" class="btn btn-gray">Back</a>
                    </div>
                    <div class="form-group">
                        <button href="" class="btn">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
