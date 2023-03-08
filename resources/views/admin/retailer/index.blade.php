@extends('layouts.app')
@section('content')
    <main id="main">
        <div class="page-title-box">
            <div class="container">
                <div class="row">
                    <div class="col flex-align gap-30">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                            <li class="breadcrumb-item active">Retailer</li>
                        </ol>
                    </div>
                    <div class="col-auto">
                        <div class="page-title-right">
                            <div class="row">
                                <div class="col-auto">
                                    <form action="{{ route('retailers.index') }}" method="GET">
                                        @csrf
                                        <div class="input-group">

                                            <select name="col_name" class="form-control" required>
                                                <option value="">--Search By--</option>
                                                <option value="name">Name</option>
                                                <option value="DBA">DBA</option>
                                                <option value="province">Province</option>
                                                <option value="location">Location</option>

                                            </select>
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                            <input type="text" class="form-control" placeholder="Search Retailer"
                                                name="retailer" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit" name="search_name">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <a href="{{ route('retailers.create') }}" class="btn">+ Add
                                Retailer</a>
                            <a href="" class="btn flex-align" data-bs-toggle="modal"
                                data-bs-target="#uploadReports"><i class="icon-upload"></i>Bulk Upload</a>
                            <div class="dropdown">
                                <a class="btn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="icon-filter"></i>
                                </a>
                                <ul class="dropdown-menu option-sub-menu">
                                    <li><a href="{{ route('retailers.index', ['Pending']) }}"><i
                                                class="icon-edit"></i>Pending</a>
                                    <li><a href="{{ route('retailers.requested') }}"><i class="icon-edit"></i>Requested</a>
                                    </li>
                                    <li><a href="{{ route('retailers.index', ['Approved']) }}"><i
                                                class="icon-edit"></i>Approved</a>
                                    </li>
                                    <li><a href="{{ route('retailers.index', ['Rejected']) }}"><i
                                                class="icon-edit"></i>Rejected</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">Retailer Managment</h4>
                    <div class="table-responsive right-bg pb-3">
                        <table class="table long-table mb-0">
                            <thead>
                                <tr>
                                    <th> Sr. </th>
                                    <th>DBA</th>
                                    <th>Owner Full Name</th>
                                    <th>Email</th>
                                    <th>Registration</th>
                                    <th>Status</th>
                                    <th>Corporate Name</th>
                                    <th>Owner Phone Number</th>

                                    @for ($i = 1; $i <= $count; $i++)
                                        <th>Street Number</th>
                                        <th>Street Name</th>
                                        <th>Postal Code</th>
                                        <th>Location</th>
                                        <th>City</th>
                                        <th>Province</th>
                                        <th>Contact Person Name At Location</th>
                                        <th>Contact Person Phone Number At Location</th>
                                    @endfor
                                    @if (empty($count))
                                        <th>Street Number</th>
                                        <th>Street Name</th>
                                        <th>Postal Code</th>
                                        <th>Location</th>
                                        <th>City</th>
                                        <th>Province</th>
                                        <th>Contact Person Name At Location</th>
                                        <th>Contact Person Phone Number At Location</th>
                                    @endif
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailers as $key => $retailer)
                                    <tr>
                                        <td> {{ $key + 1 }} </td>
                                        <td><a href="{{ route('retailers.show', [$retailer->id]) }}">{{ $retailer->DBA ? $retailer->DBA : 'Null' }}
                                        </td>
                                        </a>
                                        <td>{{ $retailer->user->name ?? 'Null' }}</td>
                                        <td> {{ $retailer->user->email ?? 'Null' }} </td>
                                        <td>
                                            @if ($retailer->user && !is_null($retailer->user->password))
                                                Registered
                                            @elseif($retailer->user)
                                                <a href="{{ route('sendEmailAgain', [$retailer->user->id]) }}">Resend
                                                    Email</a>
                                            @else
                                                ""
                                            @endif
                                        </td>
                                        <td> {{ $retailer->status ?? 'Null' }} </td>
                                        <td>{{ $retailer->corporate_name ?? 'Null' }}</td>

                                        <td>{{ $retailer->owner_phone_number ?? 'Null' }}</td>

                                        @foreach ($retailer->RetailerAddresses as $address)
                                            <td>{{ $address->street_number ?? 'Null' }}</td>
                                            <td>{{ $address->street_name ?? 'Null' }}</td>
                                            <td>{{ $address->postal_code ?? 'Null' }}</td>
                                            <td>{{ $address->location ?? 'Null' }}</td>
                                            <td>{{ $address->city ?? 'Null' }}</td>
                                            <td>{{ $address->province ?? 'Null' }}</td>
                                            <td>{{ $address->contact_person_name_at_location ?? 'Null' }}</td>
                                            <td>{{ $address->contact_person_phone_number_at_location ?? 'Null' }}</td>
                                        @endforeach

                                        @if ($retailer->RetailerAddresses->count() < $count)
                                            @for ($j = 0; $j < $count - $retailer->RetailerAddresses->count(); $j++)
                                                <td>Null</td>
                                                <td>Null</td>
                                                <td>Null</td>
                                                <td>Null</td>
                                                <td>Null</td>
                                                <td>Null</td>
                                                <td>Null</td>
                                                <td>Null</td>
                                            @endfor
                                        @endif
                                        <td>
                                            <div class="dropdown">
                                                <a class="table-option" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fal fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu option-sub-menu">
                                                    <li><a href="" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRetailer{{ $retailer->id }}"><i
                                                                class="icon-dustbin"></i>Delete</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="deleteRetailer{{ $retailer->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header p-4">
                                                    <div class="modal-heading">
                                                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i>
                                                            Delete Retailer</h4>
                                                    </div>
                                                </div>

                                                <div class="modal-body">
                                                    <h3 style="text-align: center">Are you Sure You want to Delete Retailer
                                                    </h3>

                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ route('retailers.destroy', [$retailer->id]) }}"
                                                        class="btn btn-secondary">Delete</a>
                                                    <a type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $retailers->links() !!}
                </div>

            </div>
        </section>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="uploadReports" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <div class="modal-heading">
                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i> Upload Retailers</h4>
                    </div>
                </div>
                <form action="{{ route('retailers.upload.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="file" class="form-control" name="retailers_csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#mySelect').change(function() {
                var value = $(this).val();

                if (value == 'greenline') {
                    $('.otherInput').hide();
                    $('#greenLineInput').show();

                } else {
                    $('.otherInput').show();
                    $('#greenLineInput').hide();
                }
            });
        });
    </script>

@stop
