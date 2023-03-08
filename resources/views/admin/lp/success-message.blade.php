@extends('layouts.app2')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="display: flex; justify-content: center;">
                    <a href=""
                        style="display: block; border-style: none !important; border: 0 !important;margion:auto;"><img
                            src="http://collectivedataportal.herokuapp.com/admin/images/logo.png"></a>

                </div>
                <div class="col-lg-12" style="display: flex; justify-content: center;">
                    <div style="line-height: 35px">

                        Collective Data<span style="color: #5caad2;">Portal</span>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="display: flex; justify-content: center;">
                    <img style="width: 50%" src="{{ asset('/admin/images/success.png') }}">
                </div>
            </div>
        </div>
    </section>

@stop
