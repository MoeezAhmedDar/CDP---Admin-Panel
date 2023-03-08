@extends('../layouts.app')
@section('content')
    <main id="main">
        <div class="page-title-box">
            <div class="container">
                <div class="row">
                    <div class="col flex-align gap-30">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                            <li class="breadcrumb-item active">LP</li>
                        </ol>
                    </div>
                    <div class="col-auto">

                        <div class="page-title-right">
                            <div class="row">
                                <div class="col-auto">
                                    <form action="{{ route('lps.index') }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <select name="col_name" class="form-control" required>
                                                <option value="">--Search By--</option>
                                                <option value="name">Name</option>
                                                <option value="DBA">DBA</option>

                                            </select>
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                            <input type="text" class="form-control" placeholder="Search" name="lp_name"
                                                required>
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- <a href="{{ route('lps.sample.file') }}" class="btn btn-large pull-right"><i
                                    class="icon-download-alt"> </i> Download
                                Sample </a> --}}
                            <a href="{{ route('lps.create') }}" class="btn">+ Add LP</a>
                            {{-- <a href="" class="btn flex-align" data-bs-toggle="modal"
                                data-bs-target="#uploadReports"><i class="icon-upload"></i>Bulk Upload</a> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">LP Managment</h4>
                    <div class="table-responsive pb-3">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th> Sr. </th>
                                    <th>DBA</th>
                                    <th>LP Legal Name</th>
                                    <th>Primary Contact Name</th>
                                    <th>Primary Contact Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lps as $key => $lp)
                                    <tr>
                                        <td> {{ $key + 1 }} </td>
                                        <td><a
                                                href="{{ route('lps.show', [$lp->id]) }}">{{ $lp->DBA ? Str::limit($lp->DBA, 15, $end = '...') : 'Null' }}</a>
                                        </td>
                                        <td>{{ Str::limit($lp->user->name, 15, $end = '...') }}</td>
                                        <td>{{ Str::limit($lp->primary_contact_name, 15, $end = '...') }}
                                        </td>
                                        <td>{{ Str::limit($lp->user->email, 15, $end = '...') }}
                                        </td>
                                        <td><a class="btn btn-primary" href="{{ route('lp.statement', [$lp->id]) }}">
                                                Statement
                                            </a></td>
                                        {{-- <td>
                                            <div class="dropdown">
                                                <a class="table-option" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fal fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu option-sub-menu">
                                                    <li><a href="{{ route('lps.edit', [$lp->id]) }}"><i
                                                                class="icon-edit"></i>Edit</a></li>
                                                    <li><a href="" data-bs-toggle="modal"y
                                                            data-bs-target="#deleteLP{{ $lp->id }}"><i
                                                                class="icon-dustbin"></i>Delete</a></li>

                                                    <li>
                                                        <a href="{{ route('lps.show', [$lp->id]) }}">
                                                            <i class="icon-eye"></i>View</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('lps.offers', [$lp->id]) }}"><i
                                                                class="icon-upload"></i>Offers
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="" data-bs-toggle="modal"
                                                            data-bs-target="#individualOffers{{ $lp->user->id }}"><i
                                                                class="icon-upload"></i>Upload Offers
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('lp.statement', [$lp->id]) }}"><i
                                                                class="icon-fee_structure"></i>Statement
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td> --}}
                                    </tr>
                                    <div class="modal fade" id="deleteLP{{ $lp->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header p-4">
                                                    <div class="modal-heading">
                                                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i>
                                                            Delete LP</h4>
                                                    </div>
                                                </div>

                                                <div class="modal-body">
                                                    <h3 style="text-align: center">Are you Sure You want to Delete LP</h3>
                                                </div>
                                                <div class="modal-footer">

                                                    <a href="{{ route('lps.destroy', [$lp->id]) }}"
                                                        class="btn btn-secondary"></i>Delete</a>
                                                    <a type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="individualOffers{{ $lp->user->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header p-4">
                                                    <div class="modal-heading">
                                                        <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i>
                                                            Upload Offers</h4>
                                                    </div>
                                                </div>
                                                <form action="{{ route('lp.individualOffers.csv') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type="hidden" name="lp_id" id=""
                                                            value="{{ $lp->id }}">
                                                        <div class="form-group">
                                                            <label for="">Varaiable Fee</label>
                                                            <input type="file" class="form-control" name="VariableFee"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                        <a type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $lps->links() !!}
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
                {{-- <form action="{{ route('lp.upload.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">LP</label>
                            <input type="file" class="form-control" name="lps_csv" required>
                        </div>
                        <div class="form-group">
                            <label for="">Fixed Fee</label>
                            <input type="file" class="form-control" name="FixedFee" required>
                        </div>
                        <div class="form-group">
                            <label for="">Varaiable Fee</label>
                            <input type="file" class="form-control" name="VariableFee" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                    </div>
                </form> --}}
            </div>
        </div>
    </div>

@stop
