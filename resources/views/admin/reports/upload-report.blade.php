@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">

            <div class="form-heading">
                <h5>Reports Upload</h5>
            </div>
            <div class="row mb-5">
                <div class="form-group">
                    <form novalidate action="{{ route('reports.store', $retailer->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="dropdown form-group col-md-6">
                            <label for="">Location</label>
                            <select name="location" class="form-select border-primary" required>
                                <option value="">Location</option>
                                @foreach ($retailer->RetailerAddresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->province }}, {{ $address->location }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="dropdown form-group col-md-6">
                            <label>POS</label>
                            <select id="mySelect" class="form-select border-primary" name="pos" required>
                                <option class="other" value="">POS</option>
                                <option class="other" value="cova">Cova</option>
                                <option id="greenLine" value="greenline">GreenLine</option>
                                <option value="techpos">TechPOS</option>
                                <option value="pennylane">Barnet</option>
                                <option value="ductie">Dutchie</option>
                                <option value="profittech">ProfitTech</option>
                                <option value="gobatell">Global Till</option>
                                <option value="ideal">Ideal</option>
                                <option value="epos">Other</option>
                            </select>
                        </div>
                        <div id="diagnostic_report" class="form-group otherInput">
                            <label id="lable1">Diagnostic Report</label>
                            <input type="file" class="form-control" name="diagnostic_report" required>
                        </div>
                        <div class="form-group otherInput">
                            <label id="lable2">Sales Summary Report <span>*</span></label>
                            <input type="file" class="form-control" name="sales_summary_report" id="report"
                                onChange="validate(this.value)" required>
                            <label id="file_error" class="error" for="report_name" style="display: none;">File type
                                must be
                                xlsx
                                or Csv</label>
                        </div>
                        <div class="form-group" style="display: none" id="greenLineInput">
                            <label for="">Inventory Log summary <span>*</span></label>
                            <input type="file" class="form-control" name="inventory_log_summary" required>
                        </div>
                        <button type="submit" class="btn">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function(e) {

            $('#mySelect').change(function() {
                var value = $(this).val();

                if (value == 'greenline' || value == 'epos' || value == 'techpos' || value == 'pennylane' ||
                    value ==
                    'profittech') {
                    $('.otherInput').hide();
                    $('#greenLineInput').show();

                } else if (value == 'other') {
                    $("#lable1").text("File One");
                    $("#lable2").text("File Two");
                } else if (value == 'ductie') {
                    $("#lable1").text("Inventory Receipt Detail");
                    $("#lable2").text("Roll Forward ");
                } else if (value == 'ideal') {
                    $("#lable1").text("Finalised Report");
                    $("#lable2").text("Stock Purchase Report");
                } else {
                    $("#lable1").text("Diagnostic Report");
                    $("#lable2").text("Sales Summary Report");
                    $('.otherInput').show();
                    $('#greenLineInput').hide();
                }
            });
        });
    </script>
@stop
