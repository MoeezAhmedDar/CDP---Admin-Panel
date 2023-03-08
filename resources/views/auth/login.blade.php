@extends('auth.layouts.layout')
@section('content')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <div id="wrapper">
        <div class="signup">
            <div class="content-box" style="background-image: url({{ asset('admin/images/login-bg.jpg') }}">
                <div class="text-box">
                    <h2 class="text-title large">
                        Be Seen.
                        <br>
                        Be Heard.
                        <br>
                        Be Supported.
                    </h2>
                </div>
            </div>
            <div class="login-form">
                <div class="text-center">
                    <div class="logo">
                        <a href="">
                            <img src="{{ asset('admin/images/logo.png') }}" alt="">
                        </a>
                    </div>
                    <p class="text-capitalize text-secondary mb-2">Welcome Back!</p>
                    <h2 class="text-title text-primary">
                        Login to IRCC Data Portal
                    </h2>
                </div>
                <form id="login-form" method="POST" action="/login" class="form-horizontal auth-form">
                    @csrf
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" required name="email" value="{{ old('email') }}" class="form-control"
                            placeholder="Enter Your Email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="password-field">
                            <input type="password" class="form-control" name="password" required
                                placeholder="Enter Your Password">
                            <span class="showpassword"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! NoCaptcha::renderJs() !!}
                        {!! NoCaptcha::display() !!}
                    </div>

                    <div class="form-group text-end">
                        <a href="{{ route('password.request') }}" class="text-primary">Forgot Password?</a>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn" type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("my_captcha_form").addEventListener("submit", function(evt) {

            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                //reCaptcha not verified
                alert("please verify you are humann!");
                evt.preventDefault();
                return false;
            }
            //captcha verified
            //do the rest of your validations here

        });
    </script>
@stop
