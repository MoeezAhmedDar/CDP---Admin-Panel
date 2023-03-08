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
                            <li class="breadcrumb-item active">Roles</li>
                        </ol>
                    </div>
                    <div class="col-auto">
                        <div class="page-title-right">
                            <a href="{{ route('roles.create') }}" class="btn">+Add New Role</a>
                            <a href="{{ route('dashboard') }}" class="btn">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">Existing Roles</h4>
                    <div class="table-responsive pb-3">
                        <table class="table roles mb-0" >
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <ul class="action-list">
                                                <li><a href="{{ route('roles.show', [$role->id]) }}"><i
                                                            class="icon-eye"></i></a></li>
                                                <li><a href="{{ route('roles.edit', [$role->id]) }}"><i
                                                            class="icon-edit"></i></a></li>
                                                <li><a href=""><i class="icon-dustbin"></i></a></li>
                                            </ul>
                                        </td>
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
