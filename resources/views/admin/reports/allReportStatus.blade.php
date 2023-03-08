 @extends('../layouts.app')
 @section('content')
     <?php
     use Illuminate\Http\Request;
     use Carbon\Carbon;
     ?>

     <main id="main">
         <div class="page-title-box">
             <div class="container">
                 <div class="row">
                     <div class="col flex-align gap-30">
                         <h4 class="page-title">Dashboard</h4>
                         <ol class="breadcrumb">
                             <li class="breadcrumb-item"><a href="">IRCC Data Portal</a></li>
                             <li class="breadcrumb-item active">Reports</li>
                         </ol>
                     </div>

                     <div class="col-auto">
                         <div class="page-title-right">
                             <div class="row">
                                 <div class="col-auto">
                                     <form action="{{ route('reports.monthly.status') }}" method="GET">
                                         @csrf
                                         <div class="input-group">

                                             <select name="col_name" class="form-control">
                                                 <option value="">--Search By--</option>
                                                 <option value="name">Name</option>
                                                 <option value="DBA">DBA</option>
                                                 <option value="POS">POS</option>
                                                 <option value="province">Province</option>
                                                 <option value="location">Location</option>

                                             </select>
                                             &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                             <input type="text" class="form-control" placeholder="Search"
                                                 name="report_name">
                                             &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                             <input type="month" placeholder="Select Month " class="form-control"
                                                 name="date_search"
                                                 value={{ request()->get('date_search')? request()->get('date_search'): Carbon::now()->startOfMonth()->subMonth()->format('Y-m') }}>>
                                             <div class="input-group-append">
                                                 <button class="btn btn-secondary" type="submit">
                                                     <i class="fa fa-search"></i>
                                                 </button>
                                             </div>
                                         </div>
                                     </form>
                                 </div>
                             </div>

                         </div>
                     </div>
                 </div>
             </div>
             <br>
             <section class="section">
                 <div class="container">
                     <div class="content-box listing-box">
                         <h4 class="content-title">Monthly Reports</h4>
                         <div class="table-responsive pb-3">
                             <table class="table mb-0">
                                 <thead>
                                     <tr>
                                         <th>Sr.</th>
                                         <th>DBA</th>
                                         <th>Name</th>
                                         <th>Province</th>
                                         <th>Location</th>
                                         <th>POS</th>
                                         <th>File 1</th>
                                         <th>File 2</th>
                                         <th>Date</th>
                                         <th style="text-align: center;">Action</th>
                                     </tr>
                                 </thead>

                                 <tbody>
                                     @foreach ($reports as $report)
                                         <tr>
                                             <td>{{ $loop->index + 1 }}</td>
                                             <td>{{ $report->retailer->DBA }}</td>
                                             <td>{{ $report->retailer->user->name }}</td>
                                             <td>{{ $report->province }}</td>
                                             <td>{{ $report->location }}</td>
                                             <td>
                                                 @if ($report->pos == 'gobatell')
                                                     GlobalTill
                                                 @elseif ($report->pos == 'pennylane')
                                                     Barnet
                                                 @elseif ($report->pos == 'epos')
                                                     Other Pos
                                                 @else
                                                     {{ $report->pos }}
                                                 @endif
                                             </td>
                                             <td><a href="{{ asset('reports/' . $report->file1) }}" download
                                                     class="btn btn-primary">File1</a></td>
                                             <td><a href="{{ asset('reports/' . $report->file2) }}"
                                                     class="btn btn-primary">File2</a></td>
                                             <td>{{ \Carbon\carbon::parse($report->date)->format('M-Y') }}</td>
                                             <td>

                                                 <div class="d-flex">
                                                     {{-- <ul class="action-list">
                                                         <li><a
                                                                 href="{{ route('dirty.rows', [$report->id, $report->retailer->id]) }}"><i
                                                                     class="icon-eye"></i></a>
                                                         </li>
                                                     </ul> --}}
                                                     <ul>
                                                         <a class="btn btn-primary"
                                                             href="{{ route('clean.report', [$report->id, $report->retailer->id]) }}">Sheet
                                                             </i>
                                                         </a>
                                                     </ul>
                                                     <ul>
                                                         <a class="btn btn-primary"
                                                             href="{{ route('retailer.statement', [$report->id, $report->retailer->id]) }}">
                                                             Statement
                                                         </a>
                                                     </ul>
                                                     <ul>
                                                         <a href="" class="btn" data-bs-toggle="modal"
                                                             data-bs-target="#DeleteReport{{ $report->id }}">Delete
                                                             Report</a>
                                                     </ul>
                                                 </div>
                                             </td>
                                         </tr>
                                         <div class="modal fade" id="DeleteReport{{ $report->id }}" tabindex="-1"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                             <div class="modal-dialog modal-lg modal-dialog-centered">
                                                 <div class="modal-content">
                                                     <div class="modal-header p-4">
                                                         <div class="modal-heading">
                                                             <h4 class="modal-title flex-align gap-2"><i
                                                                     class="icon-upload"></i>
                                                                 Delete Report</h4>
                                                         </div>
                                                     </div>

                                                     <div class="modal-body">
                                                         <h3 style="text-align: center">Are you Sure You want to Delete
                                                             Report</h3>
                                                     </div>
                                                     <div class="modal-footer">
                                                         <a class="btn btn-warning"
                                                             href="{{ route('delete.report', [$report->id]) }}">Delete</a>
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
                         {!! $reports->appends($_GET)->links() !!}
                     </div>
                 </div>
             </section>
     </main>

     <script>
         function getDate() {
             var today = new Date();

             var dd = today.getDate();
             var mm = today.getMonth(); //January is 0!
             var yyyy = today.getFullYear();

             if (dd < 10) {
                 dd = '0' + dd
             }

             if (mm < 10) {
                 mm = '0' + mm
             }

             today = yyyy + '-' + mm;
             console.log('Date:::', today);
             document.getElementById("date").value = today;
         }


         window.onload = function() {
             getDate();
         };
     </script>
 @stop
