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

                        IRCC Data Portal
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <div class="tab-form">
                <ul class="tabset">
                    <li class="active"><a href="#feeFixed">Fee structure (Fixed)</a></li>
                    <li><a href="#feeVariable">Fee structure (Variable)</a></li>
                    <li><a href="#feeFixedVariable">Fee structure (Fixed & Variable)</a></li>
                </ul>
                <div class="tab-content">
                    <form id="lp-fixed-fee-structure" class="form dashboard-form"
                        action="{{ route('lps.fixed.fee.store', [$lp->id]) }}" method="POST">
                        @csrf
                        <div id="feeFixed">
                            <div id="onlyFixedFee">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province_id[]">
                                            <option value="">
                                                Select Province Name</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_id }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Description & Size</label>
                                        <input type="text" name="product_description_and_size[]"
                                             required
                                            placeholder="Enter Product Description & Size">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">pre Roll</label>
                                        <input type="text" name="pre_roll[]" required
                                            placeholder="Enter Pre Roll">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Brand</label>
                                        <input type="text" name="brand[]" value="" required
                                            placeholder="Enter Brand">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial Sku</label>
                                        <input type="text" name="provincial_sku[]" required
                                            placeholder="Enter Provincial Sku">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTIN</label>
                                        <input type="text" name="gtin[]"  required
                                            placeholder="Enter GTIN">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data Fee</label>
                                        <input type="text" name="data_fee[]" required
                                            placeholder="Enter Data Fee">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cost</label>
                                        <input type="text" name="cost[]"  required
                                            placeholder="Enter Cost">
                                    </div>
                                    <div class="form-group col-md-12 space-between">

                                        <label for=""></label>
                                        <a class="btn add_more">Add More</a>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group col-md-12 space-between">
                                 <button type="submit" class="btn">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div id="feeVariable">
                        <form id="lp-variable-fee-structure" class="form dashboard-form"
                            action="{{ route('lps.variable.fee.store', [$lp->id]) }}" method="POST">
                            @csrf
                            <div id="onlyVariableFee">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province[]">
                                            <option value="">
                                               Select Province Name</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label id="province-error" class="error" for="province">This field is required.</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Category</label>
                                        <select name="category[]" class="form-select">
                                            <option value="topical1">Topical1</option>
                                            <option value="topical2">Topical2</option>
                                            <option value="topical3">Topical3</option>
                                            <option value="topical4">Topical4</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Brand</label>
                                        <input type="text" name="brand[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Name</label>
                                        <input type="text" name="product_name[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial</label>
                                        <input type="text" name="provincial[]" required placeholder="340076">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTin</label>
                                        <input type="text" name="GTin[]" required placeholder="2*60g">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>product</label>
                                        <input type="text" name="product[]" required placeholder="500">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>THC</label>
                                        <input type="text" name="thc[]" required placeholder="6">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>CBD</label>
                                        <input type="text" name="cbd[]" required placeholder="141.3">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Case</label>
                                        <input type="text" name="case[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>unit_cost</label>
                                        <input type="text" name="unit_cost[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>offer</label>
                                        <input type="date" class="input-date" name="offer[]" required placeholder="23.55">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Offer End</label>
                                        <input type="date" class="input-date" name="offer_end[]" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data</label>
                                        <input type="text" name="data[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Comments</label>
                                        <input type="text" name="comments[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Links</label>
                                        <input type="text" name="links[]" required placeholder="$3.5/unit">
                                    </div>

                                    <div class="form-group col-md-12 space-between">
                                        <label for=""></label>
                                        <button type="submit" class="btn add_more_variable_fee">Add More</button>
                                    </div>

                                </div>

                            </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn">Submit</button>
                                </div>

                        </form>
                    </div>







                    <div id="feeFixedVariable">
                        <form id="lp-fixed-variable-fee-structure" class="form dashboard-form"
                            action="{{ route('lps.fixed.and.variable.fee.store', [$lp->id]) }}" method="POST">
                            @csrf
                            <div id="onlyFixedFeeOne">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province_id[]">
                                            <option value="">
                                                Select Province Name</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_id }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Description & Size</label>
                                        <input type="text" name="product_description_and_size[]"
                                            value="" required
                                            placeholder="Enter Product Description & Size">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">pre Roll</label>
                                        <input type="text" name="pre_roll[]" value="" required
                                            placeholder="Enter Pre Roll">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Brand</label>
                                        <input type="text" name="brand[]" required
                                            placeholder="Enter Brand">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial Sku</label>
                                        <input type="text" name="provincial_sku[]" required
                                            placeholder="Enter Provincial Sku">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTIN</label>
                                        <input type="text" name="gtin[]" required
                                            placeholder="Enter GTIN">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data Fee</label>
                                        <input type="text" name="data_fee[]" required
                                            placeholder="Enter Data Fee">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cost</label>
                                        <input type="text" name="cost[]" required
                                            placeholder="Enter Cost">
                                    </div>
                                    <div class="form-group col-md-12 space-between">

                                        <label for=""></label>
                                        <a class="btn add_more_one">Add More</a>
                                    </div>

                                </div>
                            </div>






                            <div id="onlyVariableFeeOne">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province_variable[]">
                                            <option value="">Select Province Name
                                                </option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label id="province-error" class="error" for="province">This field is required.</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Category</label>
                                        <select name="category_variable[]" class="form-select">
                                            <option value="topical1">Topical1</option>
                                            <option value="topical2">Topical2</option>
                                            <option value="topical3">Topical3</option>
                                            <option value="topical4">Topical4</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Brand</label>
                                        <input type="text" name="brand_variable[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Name</label>
                                        <input type="text" name="product_name_variable[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial</label>
                                        <input type="text" name="provincial_variable[]" required placeholder="340076">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTin</label>
                                        <input type="text" name="GTin_variable[]" required placeholder="2*60g">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>product</label>
                                        <input type="text" name="product_variable[]" required placeholder="500">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>THC</label>
                                        <input type="text" name="thc_variable[]" required placeholder="6">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>CBD</label>
                                        <input type="text" name="cbd_variable[]" required placeholder="141.3">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Case</label>
                                        <input type="text" name="case_variable[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>unit_cost</label>
                                        <input type="text" name="unit_cost_variable[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>offer</label>
                                        <input type="date" class="input-date" name="offer_variable[]" required placeholder="23.55">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Offer End</label>
                                        <input type="date" class="input-date" name="offer_end_variable[]" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data</label>
                                        <input type="text" name="data_variable[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Comments</label>
                                        <input type="text" name="comments_variable[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Links</label>
                                        <input type="text" name="links_variable[]" required placeholder="$3.5/unit">
                                    </div>

                                    <div class="form-group col-md-12 space-between">
                                        <label for=""></label>
                                        <button type="submit" class="btn add_more_variable_fee_one">Add More</button>
                                    </div>

                                </div>

                            </div>

                            <div class="form-group col-md-12 space-between">
                                 <button type="submit" class="btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>





 <script>
     $(document).ready(function() {
         $(".add_more_variable_fee_one").click((e) => {
             e.preventDefault();

             $("#onlyVariableFeeOne").append(` <div class="row varaiableFeeRemove">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province_variable[]">
                                            <option value="{{ old('province_variable.0') }}">
                                                {{ old('province.0', 'Select Province Name') }}</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label id="province-error" class="error" for="province">This field is required.</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Category</label>
                                        <select name="category_variable[]" class="form-select">
                                            <option value="topical1">Topical1</option>
                                            <option value="topical2">Topical2</option>
                                            <option value="topical3">Topical3</option>
                                            <option value="topical4">Topical4</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Brand</label>
                                        <input type="text" name="brand_variable[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Name</label>
                                        <input type="text" name="product_name_variable[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial</label>
                                        <input type="text" name="provincial_variable[]" required placeholder="340076">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTin</label>
                                        <input type="text" name="GTin_variable[]" required placeholder="2*60g">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>product</label>
                                        <input type="text" name="product_variable[]" required placeholder="500">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>THC</label>
                                        <input type="text" name="thc_variable[]" required placeholder="6">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>CBD</label>
                                        <input type="text" name="cbd_variable[]" required placeholder="141.3">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Case</label>
                                        <input type="text" name="case_variable[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>unit_cost</label>
                                        <input type="text" name="unit_cost_variable[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>offer</label>
                                        <input type="date" class="input-date" name="offer_variable[]" required placeholder="23.55">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Offer End</label>
                                        <input type="date" class="input-date" name="offer_end_variable[]" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data</label>
                                        <input type="text" name="data_variable[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Comments</label>
                                        <input type="text" name="comments_variable[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Links</label>
                                        <input type="text" name="links_variable[]" required placeholder="$3.5/unit">
                                    </div>
                                  <div class="form-group col-md-12 space-between">

                                        <label for=""></label>
                                        <a href="" class="btn btn-danger removeRowvaraiable">Remove</a>
                                    </div>
                                    </div>
                                    `);
         });




         $(".add_more_one").click((e) => {
                e.preventDefault();

                $("#onlyFixedFeeOne").append(` <div class="row only_fee_structure">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province_id[]">
                                            <option value="{{ old('province.0') }}">
                                                {{ old('province.0', 'Select Province Name') }}</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_id }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Description & Size</label>
                                        <input type="text" name="product_description_and_size[]"
                                             required
                                            placeholder="Enter Product Description & Size">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">pre Roll</label>
                                        <input type="text" name="pre_roll[]" required
                                            placeholder="Enter Pre Roll">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Brand</label>
                                        <input type="text" name="brand[]" required
                                            placeholder="Enter Brand">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial Sku</label>
                                        <input type="text" name="provincial_sku[]"
                                         required
                                            placeholder="Enter Provincial Sku">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTIN</label>
                                        <input type="text" name="gtin[]" required
                                            placeholder="Enter GTIN">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data Fee</label>
                                        <input type="text" name="data_fee[]" required
                                            placeholder="Enter Data Fee">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cost</label>
                                        <input type="text" name="cost[]" required
                                            placeholder="Enter Cost">
                                    </div>
                                    <div class="form-group col-md-12 space-between">

                                        <label for=""></label>
                                        <a href="" class="btn btn-danger removeRow">Remove</a>
                                    </div>

                                </div>

                            `);
            });
     });
 </script>

    <script>
        $(document).ready(function() {
            $('.error').hide();
            var fixedFeeStructure = $("#lp-fixed-fee-structure");
            var variableFeeStructure = $("#lp-variable-fee-structure");
            varFixedVariableFeeStructure=$('#lp-fixed-variable-fee-structure');

            varFixedVariableFeeStructure.validate({
                rules: {
                    commission: {
                        required: true,
                    },

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });


            fixedFeeStructure.validate({
                rules: {
                    commission: {
                        required: true,
                    },

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            variableFeeStructure.validate({
                rules: {
                    province: {
                        required: true,
                    },

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });



            $(".add_more_variable_fee").click((e) => {
                e.preventDefault();

                $("#onlyVariableFee").append(` <div class="row varaiableFeeRemove">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province[]">
                                            <option value="{{ old('province.0') }}">
                                                {{ old('province.0', 'Select Province Name') }}</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label id="province-error" class="error" for="province">This field is required.</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Category</label>
                                        <select name="category[]" class="form-select">
                                            <option value="topical1">Topical1</option>
                                            <option value="topical2">Topical2</option>
                                            <option value="topical3">Topical3</option>
                                            <option value="topical4">Topical4</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Brand</label>
                                        <input type="text" name="brand[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Name</label>
                                        <input type="text" name="product_name[]" required
                                            placeholder="Glow Day/Night Cream">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial</label>
                                        <input type="text" name="provincial[]" required placeholder="340076">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTin</label>
                                        <input type="text" name="GTin[]" required placeholder="2*60g">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>product</label>
                                        <input type="text" name="product[]" required placeholder="500">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>THC</label>
                                        <input type="text" name="thc[]" required placeholder="6">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>CBD</label>
                                        <input type="text" name="cbd[]" required placeholder="141.3">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Case</label>
                                        <input type="text" name="case[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>unit_cost</label>
                                        <input type="text" name="unit_cost[]" required placeholder="23.55">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>offer</label>
                                        <input type="date" class="input-date" name="offer[]" required placeholder="23.55">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Offer End</label>
                                        <input type="date" class="input-date" name="offer_end[]" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data</label>
                                        <input type="text" name="data[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Comments</label>
                                        <input type="text" name="comments[]" required placeholder="$3.5/unit">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Links</label>
                                        <input type="text" name="links[]" required placeholder="$3.5/unit">
                                    </div>
                                  <div class="form-group col-md-12 space-between">

                                        <label for=""></label>
                                        <a href="" class="btn btn-danger removeRowvaraiable">Remove</a>
                                    </div>
                                    </div>
                                    `);
            });



            $(".add_more").click((e) => {
                e.preventDefault();

                $("#onlyFixedFee").append(` <div class="row only_fee_structure">
                                    <div class="form-group col-md-4">
                                        <label>Province</label>
                                        <select class="select2 form-control mb-3 custom-select" required name="province_id[]">
                                            <option value="{{ old('province.0') }}">
                                                {{ old('province.0', 'Select Province Name') }}</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->province_id }}">{{ $province->province_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Product Description & Size</label>
                                        <input type="text" name="product_description_and_size[]"
                                             required
                                            placeholder="Enter Product Description & Size">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">pre Roll</label>
                                        <input type="text" name="pre_roll[]" required
                                            placeholder="Enter Pre Roll">

                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="">Brand</label>
                                        <input type="text" name="brand[]" required
                                            placeholder="Enter Brand">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Provincial Sku</label>
                                        <input type="text" name="provincial_sku[]"  required
                                            placeholder="Enter Provincial Sku">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>GTIN</label>
                                        <input type="text" name="gtin[]" required
                                            placeholder="Enter GTIN">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Data Fee</label>
                                        <input type="text" name="data_fee[]" required
                                            placeholder="Enter Data Fee">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Cost</label>
                                        <input type="text" name="cost[]" required
                                            placeholder="Enter Cost">
                                    </div>
                                    <div class="form-group col-md-12 space-between">

                                        <label for=""></label>
                                        <a href="" class="btn btn-danger removeRow">Remove</a>
                                    </div>

                                </div>

                            `);
            });

             $(document).on("click", ".removeRow", function(e) {
                e.preventDefault();
                $(this).closest('.only_fee_structure').remove();
            });


            $(document).on("click", ".removeRowvaraiable", function(e) {
                e.preventDefault();
                console.log("hello");
                $(this).closest('.varaiableFeeRemove').remove();
            });
        });
    </script>

@stop
