@extends('layouts.app')
@section('content')

    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Edit Retailer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <form id="retailer-form" class="form dashboard-form" action="{{ route('retailers.update.profile', [$retailer->id]) }}"
                method="POST">
                @csrf
                @method('put')
                <div class="form-heading">
                    <h5>Retailer Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Corporate Full name<span style="color: red;">*</span></label>
                        <input type="text" value="{{ old('corporate_name', $retailer->corporate_name) }}"
                            placeholder="Enter Corporate Name" name="corporate_name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>DBA<span style="color: red;">*</span></label>
                        <input type="text" value="{{ old('DBA', $retailer->DBA) }}" required placeholder="Enter DBA"
                            name="DBA">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owner Name<span style="color: red;">*</span></label>
                        <input type="text" value="{{ old('owner_name', $retailer->user->name) }}" required
                            placeholder="Enter Owner Name" name="owner_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owners Phone Number</label>
                        <input type="number" value="{{ old('owner_phone_number', $retailer->owner_phone_number) }}"
                             placeholder="Enter Owner Phone Number" name="owner_phone_number">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email<span style="color: red;">*</span></label>
                        <input type="text" value="{{ old('email', $retailer->user->email) }}" required
                            placeholder="Enter Email" name="email">
                    </div>
                     <div class="form-group col-md-4">
                        <label>Status<span style="color: red;">*</span></label>
                        <select class="select2 form-control mb-3 custom-select" required name="status"
                            style="width: 100%; height:36px;">

                            @foreach ($statuses as $status)
                                <option {{ $status == $retailer->status ? 'selected' : '' }} value="{{ $status }}">
                                    {{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @foreach ($retailer->RetailerAddresses as $address)
                <div class="form-heading">
                    <h5>Address {{ $loop->index+1 }}</h5>
                </div>
                <div id="addresses" class="col-md-12 row address">
                    <div class="row mb-5">
                        <div class="form-group col-md-4">
                            <label>Street Number</label>
                            <input type="number" placeholder="Enter Street Number"  name="street_number[]"
                                value="{{ $address->street_number }}">
                        </div>
                        <input type="hidden" name="address_id[]" value="{{ $address->id }}">
                        <div class="form-group col-md-4">
                            <label>Street Name<span style="color: red;">*</span></label>
                            <input type="text" placeholder="Enter Street Name" required name="street_name[]"
                                value="{{ old('street_name.' . $loop->index, $address->street_name) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Postal Code<span style="color: red;">*</span></label>
                            <input type="text" value="{{ old('postal_code.' . $loop->index, $address->postal_code) }}"
                                required placeholder="Enter Postal Code" name="postal_code[]">
                        </div>
                        <div class="form-group col-md-8 row citiesProvinces">
                            <div class="form-group col-md-6">
                                <label>Province<span style="color: red;">*</span></label>
                                <select class="select2 form-control mb-3 custom-select province" required name="province[]">
                                    <option value="{{ old('province.0', $address->province) }}">
                                        {{ old('province.0', $address->province) }}</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>City<span style="color: red;">*</span></label>
                                <select class="select2 form-control mb-3 custom-select city" name="city[]" required>
                                    <option value="{{ old('city.0', $address->city) }}">
                                        {{ old('city.0', $address->city) }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Location<span style="color: red;">*</span></label>
                            <input type="text" placeholder="Enter Location" name="location[]"
                                value="{{ old('location.' . $loop->index, $address->location) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Contact person name at location<span style="color: red;">*</span></label>
                            <input type="text" required placeholder="Enter name" name="contact_person_name_at_location[]"
                                value="{{ old('contact_person_name_at_location.' . $loop->index, $address->contact_person_name_at_location) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Contact person Phone Number at location</label>
                            <input type="number"  placeholder="Enter Phone number"
                                name="contact_person_phone_number_at_location[]"
                                value="{{ old('contact_person_phone_number_at_location.' . $loop->index, $address->contact_person_phone_number_at_location) }}">
                        </div>
                            <div class="col-md-12 text-end form-group align-self-end">
                                <a href="" class="btn btn-danger removeRow">Remove</a>
                            </div>
                    </div>
                 </div>

                @endforeach
                <div class="col-md-12 row align-items-center">
                    <div class="form-group  form-check checkbox  checkbox-blue col-md-4">
                        <input class="form-check-input" type="radio" name="aggregated_data" value="Yes"
                            @if (old('aggregated_data') == 'Yes' || $retailer->aggregated_data == 'Yes') checked @endif id="aggregatedYes">
                        <label class="form-check-label" for="aggregatedYes">
                            yes (aggregated & location data)
                        </label>
                    </div>
                    <div class="form-group form-check checkbox checkbox-blue col-md-4">
                        <input class="form-check-input" type="radio" name="aggregated_data" value="No"
                            @if (old('aggregated_data') == 'No' || $retailer->aggregated_data == 'No') checked @endif id="aggregatedNo">
                        <label class="form-check-label" for="aggregatedNo">
                            If no(Aggregated Data)
                        </label>
                    </div>
                </div>
                <div class="form-group ">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $(document).on("change", ".province", function(e) {

                let province = $(this).closest('.province').find(":selected").val();
                let currentRow = $(this).closest('.citiesProvinces');

                $.ajax({
                    url: "{{ route('cities.get') }}",
                    type: "GET",
                    data: {
                        'province': province,
                    },
                    success: function(response) {
                        currentRow.find('.city').empty();
                        currentRow.find('.city').append(
                            `<option value="">Select City Name</option>`);

                        $.each(response, function(key, value) {
                            currentRow.find('.city').append(`<option value="` + value
                                .city + `">` +
                                value.city + `</option>`);
                        });
                    },
                    error: function(reject) {
                        console.log(reject);
                    },
                });
            });

            var createRetailerForm = $("#retailer-form");

            createRetailerForm.validate({
                rules: {
                    email: {
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 20
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        maxlength: 20,
                    },

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

    $(".add_more").click((e) => {
        e.preventDefault();
        $("#addresses").append(`<div class="col-md-12 row address"> <div class="form-group col-md-4">
                            <label>Street Number</label>
                            <input type="number" placeholder="Enter Street Number" required name="street_number[]">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Street Name</label>
                            <input type="text" placeholder="Enter Street Name" required name="street_name[]"
                                >
                        </div>
                        <div class="form-group col-md-4">
                            <label>Postal Code</label>
                            <input type="text" name="postal_code[]" required
                                placeholder="Enter Postal Code" name="owner_name">
                        </div>
                        <div class="col-md-8 row citiesProvinces flex-grow-1">
                        <div class="form-group col-md-6">
                            <label>Province</label>
                            <select class="select2 form-control mb-3 custom-select province" required name="province[]">
                                <option value="">Select Province Name</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <select class="select2 form-control mb-3 custom-select city" name="city[]" required>
                                <option value="">Select City Name</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Location</label>
                            <input type="text" required placeholder="Enter location" name="location[]"
                                >
                        </div>
                        <div class="form-group col-md-4">
                            <label>Contact person name at location</label>
                            <input type="text" required placeholder="Enter name" name="contact_person_name_at_location[]"
                                >
                        </div>
                        <div class="form-group col-md-4">
                            <label>Contact person Phone Number at location</label>
                            <input type="number"  placeholder="Enter Phone number"
                                name="contact_person_phone_number_at_location[]"
                                >
                        </div>
                        <div class="col-md-12 text-end form-group align-self-end">
                            <a href="" class="btn btn-danger removeRow">Remove</a>
                        </div> </div>`);
    });

        // $(document).on("click", ".removeRow", function(e) {
        //     e.preventDefault();
        //     $(this).closest('.address').remove();
        // });

        // $(".removeRow").click(function(e){
        //     e.preventDefault();
        //     $(this).closest('.address').remove();
        // });

        });
    </script>
@stop
