@extends('layouts.app')
@section('content')
    <style>
        textarea {
            resize: none;
            vertical-align: top;
            height: 100px;
            line-height: normal;
        }
    </style>
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Edit Dirty Rows</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form  class="form dashboard-form" action="{{ route('dirty.rows.update', [$covaReport->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-heading">
                    <h5>Clean Sheet Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <input type="hidden" name="province" value="{{$covaReport->reportsubmission->province}}">
                        <input type="hidden" name="id" value="{{$covaReport->id}}">
                        <label>SKU</label>
                        <input type="text" name="sku" value="{{$covaReport->sku}}"
                            placeholder="Enter Name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Product Name</label>
                        <input type="text" name="product_name" value="{{$covaReport->product_name}}"
                            placeholder="Product Name" class="form-control input-lg">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Category</label>
                        <input type="text" name="category" value="{{$covaReport->category}}"
                            placeholder="Category">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Brand</label>
                        <input type="text" name="brand"
                            value="{{$covaReport->brand}}"
                            placeholder="Brand">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Items Sold</label>
                        <input type="text" name="sold"
                            value="{{$covaReport->sold}}"
                            placeholder="Items Sold">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Items Purchased</label>
                        <input type="text" name="purchased"
                            value="{{$covaReport->purchased}}"
                            placeholder="Items Purchased">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Barcode</label>
                        <input type="text" name="barcode"
                            value="{{$covaReport->barcode}}"
                            placeholder="Barcode">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Average Price</label>
                        <input type="text" name="average_price"
                            value="{{$covaReport->average_price}}"
                            placeholder="Average Price">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Average Cost</label>
                        <input type="text" name="average_cost"
                            value="{{$covaReport->average_cost}}"
                            placeholder="Average Cost">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Comments</label>
                        <textarea type="text" name="comments"
                            placeholder="Comments">{{$covaReport->comments}} </textarea>
                    </div>

                </div>

                <div class="form-group ">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
        </div>
    </section>

@stop
