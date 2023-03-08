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
                            <li class="breadcrumb-item active">User</li>
                        </ol>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admins.create') }}" class="btn">+ Add User</a>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">Existing Users</h4>
                    <div class="table-responsive pb-3">
                        <table class="table mb-0" >
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Full Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($superAdmins as $superAdmin)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            {{-- <div class="user-img">
                                                <img src="http://127.0.0.1:8000/admin/images/user-02.png" alt="">
                                            </div> --}}
                                            {{ Str::limit($superAdmin->user->name, 20, $end = '...') }}
                                        </td>
                                        <td>{{ Str::limit($superAdmin->phone_number, 20, $end = '...') }}
                                        </td>
                                        <td>{{ Str::limit($superAdmin->user->email, 20, $end = '...') }}
                                        </td>
                                        <td>{{ Str::limit($superAdmin->address, 20, $end = '...') }}
                                        </td>
                                        <td>{{ $superAdmin->user->roles->pluck('name')[0] ?? '' }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <ul class="action-list">
                                                    <li><a href="{{ route('admins.edit', [$superAdmin->id]) }}"><i
                                                                class="fa fa-edit"></i></a>
                                                        <a href="{{ route('admins.destroy', [$superAdmin->id]) }}"><i
                                                                class="fa fa-trash pl-2"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        {{-- <td>
                                            <div class="dropdown">
                                                <a class="table-option" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fal fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu option-sub-menu">
                                                    <li><a href="{{ route('admins.edit', [$superAdmin->id]) }}"><i
                                                                class="icon-edit"></i>Edit</a></li>
                                                    <li><a href=""><i class="icon-dustbin"></i>Delete</a></li>
                                                    <li><a href="{{ route('admins.show', [$superAdmin->id]) }}"><i
                                                                class="icon-eye"></i>View</a></li>
                                                </ul>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="text-end mb-4"> --}}
                {{-- <a href="" class="btn"><i class="icon-download"></i> Download Report</a> --}}
                {{-- </div> --}}

            </div>
        </section>
    </main>
@stop
