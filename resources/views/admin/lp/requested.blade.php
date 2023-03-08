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
                            <a href="{{ route('lps.index') }}" class="btn">Back</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">LP Managment</h4>
                    <div class="table-responsive right-bg pb-3">
                        <table class="table long-table mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check checkbox">
                                            <input class="form-check-input" type="checkbox" name="selectAll" id="selectAll">
                                            <label class="form-check-label" for="selectAll">
                                            </label>
                                        </div>
                                    </th>
                                    <th>LP Legal Name</th>
                                    <th>Primary Contact Email</th>
                                    <th>Primary Contact Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lps as $lp)
                                    <tr>
                                        <td>
                                            <div class="form-check checkbox">
                                                <input class="form-check-input" type="checkbox"
                                                    name="retailer-{{ $loop->index + 1 }}"
                                                    id="retailer-{{ $loop->index + 1 }}">
                                                <label class="form-check-label" for="retailer-{{ $loop->index + 1 }}">
                                                </label>
                                            </div>
                                        </td>
                                        <td>{{ $lp->user->name }}</td>
                                        <td>{{ $lp->user->email }}</td>
                                        <td>{{ $lp->primary_contact_phone }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a class="table-option" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fal fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu option-sub-menu">
                                                    <li><a href="{{ route('lps.edit.requested', [$lp->id]) }}"><i
                                                                class="icon-edit"></i>Edit</a></li>
                                                    <li><a href=""><i class="icon-dustbin"></i>Delete</a></li>
                                                    {{-- <li><a href="{{ route('lps.show', [$lp->id]) }}"><i
                                                                class="icon-eye"></i>View</a></li> --}}
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $lps->links() !!}
                </div>
                <div class="text-end mb-4">
                    <a href="" class="btn"><i class="icon-download"></i> Download Report</a>
                </div>
            </div>
        </section>
    </main>
@stop
