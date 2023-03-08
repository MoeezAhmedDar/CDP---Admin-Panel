@extends('layouts.app')
@section('content')

    <div class="page-title-box">
        <div class="container">
            <div class="row">
                <div class="col flex-align gap-30">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                        <li class="breadcrumb-item active">Show Retailer</li>
                    </ol>
                </div>
                <div class="col-auto">
                    <div class="page-title-right">
                        <a href="{{ route('reorts.create', [$retailer->id]) }}" class="btn flex-align upload-btn"><i
                                class="icon-upload"></i>Add
                            Report</a>
                        <a href="{{ route('retailers.edit', [$retailer->id]) }}" class="btn flex-align upload-btn"><i
                                class="fas fa-edit"></i>Edit Retailer</a>
                        <a href="{{ route('carveout.index', [$retailer->id]) }}" class="btn flex-align upload-btn"><i
                                class="fas fa-edit"></i>Carve Outs</a>
                        <a href="{{ route('retailers.address.create', [$retailer->id]) }}"
                            class="btn flex-align upload-btn"><i class="fa fa-map-marker"></i>Add Location</a>
                        <a href="{{ route('retailers.reports', [$retailer->id]) }}" class="btn flex-align upload-btn"><i
                                class="fa fa-map-marker"></i>Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <form class="form dashboard-form">
                <div class="form-heading">
                    <h5>Retailer Info</h5>
                </div>
                <div class="row mb-5">
                    <div class="form-group col-md-4">
                        <label>Corporate name</label>
                        <input type="text" readonly value="{{ old('corporate_name', $retailer->corporate_name) }}"
                            name="corporate_name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>DBA</label>
                        <input type="text" readonly value="{{ old('DBA', $retailer->DBA) }}" required name="DBA">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owner Name</label>
                        <input type="text" readonly value="{{ old('owner_name', $retailer->user->name) }}" required
                            placeholder="Enter Owner Name" name="owner_name">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Owners Phone Number</label>
                        <input type="text" readonly
                            value="{{ old('owner_phone_number', $retailer->owner_phone_number) }}" required
                            placeholder="Enter Owner Phone Number" name="owner_phone_number">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="text" readonly value="{{ old('email', $retailer->user->email) }}" required
                            placeholder="Enter Email" name="email">
                    </div>
                    @foreach ($retailer->RetailerAddresses as $address)
                        <div class="form-heading">
                            <h5>Address</h5>
                        </div>
                        <div class="row mb-5">
                            <div class="form-group col-md-4">
                                <label>Street Number</label>
                                <input type="number" readonly required name="street_number[]"
                                    value="{{ old('street_number.' . $loop->index, $address->street_number) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Street Name</label>
                                <input type="text" readonly required name="street_name[]"
                                    value="{{ old('street_name.' . $loop->index, $address->street_name) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Postal Code</label>
                                <input type="text" readonly class="form-control input-lg" required
                                    value="{{ old('postal_code.' . $loop->index, $address->postal_code) }}"
                                    name="postal_code[]">
                            </div>
                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" readonly required name="city[]"
                                    value="{{ old('city.' . $loop->index, $address->city) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Province</label><input type="text" readonly required name="province[]"
                                    value="{{ old('province.' . $loop->index, $address->province) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Location</label>
                                <input type="text" readonly required name="province[]"
                                    value="{{ old('location.' . $loop->index, $address->location) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact person name at location</label>
                                <input type="text" readonly required name="contact_person_name_at_location[]"
                                    value="{{ old('contact_person_name_at_location.' . $loop->index, $address->contact_person_name_at_location) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact person Phone Number at location</label>
                                <input type="number" required readonly
                                    value="{{ old('contact_person_phone_number_at_location.' . $loop->index, $address->contact_person_phone_number_at_location) }}">
                            </div>
                            <div class="col-md-12 row align-items-center">
                                @if ($retailer->aggregated_data == 'Yes')
                                    <div class="form-group  form-check checkbox  checkbox-blue col-md-4">
                                        <input class="form-check-input" type="radio" name="aggregated"
                                            @if (old('aggregated_data') == 'Yes' || $retailer->aggregated_data == 'Yes') checked @endif id="aggregatedYes">
                                        <label class="form-check-label" for="aggregatedYes">
                                            yes (aggregated & location data)
                                        </label>
                                    </div>
                                @else
                                    <div class="form-group form-check checkbox checkbox-blue col-md-4">
                                        <input class="form-check-input" type="radio" name="aggregated"
                                            @if (old('aggregated_data') == 'No' || $retailer->aggregated_data == 'No') checked @endif id="aggregatedNo">
                                        <label class="form-check-label" for="aggregatedNo">
                                            If no(Aggregated Data)
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
            </form>
        </div>
    </section>
@stop
