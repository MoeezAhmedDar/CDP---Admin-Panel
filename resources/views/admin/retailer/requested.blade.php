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
                            <li class="breadcrumb-item active">Retailer</li>
                        </ol>
                    </div>
                    <div class="col-auto">
                        <div class="page-title-right">
                            <a href="{{ route('retailers.index') }}" class="btn">Back</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="content-box listing-box">
                    <h4 class="content-title">Retailer Managment</h4>
                    <div class="table-responsive pb-3">
                        <table class="table roles mb-0">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Owner Name</th>
                                    <th>Email</th>
                                    <th>Owners Phone Number</th>
                                    <th>Registration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retailers as $retailer)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $retailer->user->name }}</td>
                                        <td>{{ $retailer->user->email }}</td>
                                        <td>{{ $retailer->owner_phone_number }}</td>
                                        <td>
                                            <a href="{{ route('sendRegistrationAgain', [$retailer->user->id]) }}">Resend
                                                Email</a>
                                        </td>
                                        <td>
                                            <ul class="action-list">

                                                <li><a href="{{ route('retailers.edit.requested', [$retailer->id]) }}"><i
                                                            class="icon-edit"></i></a></li>
                                                <li><a href=""><i class="icon-dustbin"></i></a></li>
                                            </ul>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {!! $retailers->links() !!}
                </div>
            </div>
        </section>
    </main>
@stop
