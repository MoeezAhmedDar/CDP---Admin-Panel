@extends('layouts.app')
@section('content')
    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Add Addresses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="container">
            <form id="address-form" class="form dashboard-form"  action="{{ route('retailers.address.store',[$retailer->id]) }}"
                method="POST"
                onsubmit="return validate()">
                @csrf
                <div class="form-heading">
                    <h5>Address</h5>
                </div>

                <div class="row mb-5">
                    <div id="addresses" class="col-md-12 row">

                    </div>
                    <div class="form-group col-md-4">
                    </div>
                    <div class="form-group col-md-4">
                    </div>
                    <div class="col-md-4 text-end form-group align-self-end">
                        <a href="" class="btn add_more">Add More</a>
                    </div>
                </div>

                <div class="form-group ">
                    <input type="button" class="btn submit-form" id="submit-form" value="Submit" />
                </div>
            </form>
        </div>

    </section>
    <script>
        $(document).ready(function(e) {
            var totalRows = 0;

            function AddFormRow(){
                $("#addresses").append(`
                        <div class="col-md-12 row address">
                            <div class="form-group col-md-4">
                                <label>Street Number<span style="color:red">*</span></label>
                                <input type="number" placeholder="Enter Street Number" required name="street_number" id="street_number-${totalRows}" >
                                </div>
                            <div class="form-group col-md-4">
                                <label>Street Name<span style="color:red">*</span></label>
                                <input type="text" placeholder="Enter Street Name" required name="street_name" id="street_name-${totalRows}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Postal Code<span style="color:red">*</span></label>
                                <input type="text" name="postal_code" required
                                    placeholder="Enter Postal Code"  id="postal_code-${totalRows}">
                            </div>
                            <div class="col-md-8 row citiesProvinces flex-grow-1">
                                <div class="form-group col-md-6">
                                    <label>Province<span style="color:red">*</span></label>
                                    <select class="select2 form-control mb-3 custom-select province"  name="province[]" id="province-${totalRows}" required>
                                        <option value="">Select Province Name</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->province_name }}">{{ $province->province_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 cityError">
                                    <label>City<span style="color:red">*</span></label>
                                    <select class="select2 form-control mb-3 custom-select city" name="city[]" id="city-${totalRows}" required>
                                        <option value="">Select City Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Location<span style="color:red">*</span></label>
                                <input type="text" required placeholder="Enter location" name="location[]"  id="location-${totalRows}" >
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact person name at location<span style="color:red">*</span></label>
                                <input type="text" required placeholder="Enter name" name="contact_person_name_at_location[]" id="contact_person_name_at_location-${totalRows}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact person Phone Number at location</label>
                                <input type="number"  placeholder="Enter Phone number"
                                    name="contact_person_phone_number_at_location[]" id="contact_person_phone_number_at_location-${totalRows}">
                            </div>
                            ${totalRows > 0 ? '<div class="col-md-4 text-end form-group align-self-end"><a href="" class="btn btn-danger removeRow ">Remove</a></div>' : ''}
                            </div>
                        </div>`
                    );

                    totalRows++;
                    console.log('rows.....',totalRows);
                }

                AddFormRow();

                function validateForm (){
                    // reset errors
                    $('.form-error').remove();
                    let isValid = true;
                    $('[required]').each(function(e) {
                        if ($(this).is(':invalid') || !$(this).val()){
                            $(this).after("<span class='form-error' style='color:red;'> Please Fill this Feild </span>")
                            isValid = false;
                        }
                    })
                    return isValid
                }

                $(".add_more").click((e) => {
                    e.preventDefault();
                    if(!validateForm()) return;

                    AddFormRow();
                });

                $(document).on("click", ".removeRow", function(e) {
                    totalRows--;
                    e.preventDefault();
                    $(this).closest('.address').remove();
                });

                $(document).on('click','.submit-form',function(e) {
                    e.preventDefault()
                    if(!validateForm()) return

                    const formData = {
                        street_number: [],
                        street_name: [],
                        postal_code: [],
                        province: [],
                        city: [],
                        location: [],
                        contact_person_name_at_location: [],
                        contact_person_phone_number_at_location: [],
                    }

                    for(let i=0; i < totalRows; i++){
                        formData.street_number.push(document.getElementById(`street_number-${i}`).value);
                        formData.street_name.push(document.getElementById(`street_name-${i}`).value);
                        formData.postal_code.push(document.getElementById(`postal_code-${i}`).value);
                        formData.province.push(document.getElementById(`province-${i}`).value);
                        formData.city.push(document.getElementById(`city-${i}`).value);
                        formData.location.push(document.getElementById(`location-${i}`).value);
                        formData.contact_person_name_at_location.push(document.getElementById(`contact_person_name_at_location-${i}`).value);
                        formData.contact_person_phone_number_at_location.push(document.getElementById(`contact_person_phone_number_at_location-${i}`).value);
                    }

                    // console.log('formData',formData)
                    $(".submit-form").prop("value", "Saving...");

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('input[type="submit"]').attr('disabled','disabled');
                    $.ajax({
                        url: "{{route('retailers.address.store',[$retailer->id])}}",
                        type: "GET",
                        data: formData

                    }).done(function (res) {
                        window.location.href = '{{ route('retailers.index')}}';
                     }).fail(function (jqXHR, textStatus) {
                        console.log(jqXHR);
                        console.log(textStatus);

                //showing validation errors here? and how to show?
                      });
                });

            $(document).on("change",".city",function(e){
            $(this).parent('.cityError').find(".error").hide();
        });


           $(document).on("change",".province",function(e){

                let province=$(this).closest('.province').find(":selected").val();
                let currentRow=$(this).closest('.citiesProvinces');

                $.ajax({
                    url: "{{route('cities.get')}}",
                    type: "GET",
                    data: {
                        'province':province,
                    },
                    success: function(response) {
                        currentRow.find('.city').empty();
                        currentRow.find('.city').append(`<option value="">Select City Name</option>`);

                        $.each(response,function(key,value){
                            currentRow.find('.city').append(`<option value="`+value.city+`">`+value.city+`</option>`);
                        });
                    },
                    error:function(reject){
                        console.log(reject);
                    },
                });
            });
        });
    </script>
@stop
