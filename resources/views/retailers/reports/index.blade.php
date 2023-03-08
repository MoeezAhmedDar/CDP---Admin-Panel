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
                             <li class="breadcrumb-item active">Reports</li>
                         </ol>
                     </div>
                     <div class="col-auto">
                         <div class="page-title-right">
                             @if (
                                 $date == null ||
                                     (now()->format('d') >= Carbon\Carbon::parse($date->starting_date)->format('d') &&
                                         now()->format('d') <= Carbon\Carbon::parse($date->ending_date)->format('d')))
                                 <a href="" class="btn flex-align upload-btn" data-bs-toggle="modal"
                                     data-bs-target="#uploadReports"><i class="icon-upload"></i>Upload Report</a>
                             @endif
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <section class="section">
             <div class="container">
                 <div class="content-box listing-box">
                     <h4 class="content-title">Monthly Reports</h4>
                     <div class="table-responsive pb-3">
                         <table class="table mb-0">
                             <thead>
                                 <tr>
                                     <th>Sr.</th>
                                     <th>Name</th>
                                     <th>Province</th>
                                     <th>Location</th>
                                     <th>Date</th>
                                     <th>POS</th>
                                     <th>Status</th>
                                     <th>Action</th>
                                 </tr>
                             </thead>

                             <tbody>
                                 @foreach ($retailerReports as $retailer)
                                     @foreach ($retailer->ReportStatus as $status)
                                         <tr>
                                             <td> {{ $loop->index + 1 }} </td>
                                             <td>{{ $retailer->user->name }}</td>
                                             <td> {{ $status->province }} </td>
                                             <td> {{ $status->location }} </td>
                                             <td>{{ \Carbon\carbon::parse($status->date)->format('M-Y') }}</td>
                                             <td>
                                                 @if ($status->pos == 'gobatell')
                                                     GlobalTill
                                                 @elseif ($status->pos == 'pennylane')
                                                     Barnet
                                                 @elseif ($status->pos == 'epos')
                                                     Other Pos
                                                 @else
                                                     {{ $status->pos }}
                                                 @endif
                                             </td>
                                             <td>{{ $status->status }}</td>
                                             <td>
                                                 {{-- @if ($status->pos == 'other' || $status->pos == 'techpos')
                                                     <ul class="action-list">
                                                         <li><a href="#"><i class="icon-eye"></i></a>
                                                         </li>
                                                     </ul>
                                                 @else
                                                     <ul class="action-list">
                                                         <li><a href="{{ route('cova.monthly.report', $status->id) }}"><i
                                                                     class="icon-eye"></i></a></li>
                                                     </ul>
                                                 @endif --}}
                                                 <ul class="action-list">
                                                     <li><a href="#"><i class="icon-eye"></i></a>
                                                     </li>
                                                 </ul>
                                             </td>
                                         </tr>
                                     @endforeach
                                 @endforeach
                             </tbody>

                         </table>
                     </div>
                     {{-- {!! $covaReports->links() !!} --}}
                 </div>
             </div>
         </section>
     </main>

     <!-- Modal -->
     <div class="modal fade" id="uploadReports" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg modal-dialog-centered">
             <div class="modal-content">
                 <form id="report-form" action="{{ route('reports.store', [Auth::user()->userable->id]) }}" method="POST"
                     enctype="multipart/form-data" novalidate>
                     @csrf
                     <div class="modal-header p-4">
                         <div class="modal-heading gap-2">
                             <h4 class="modal-title flex-align gap-2"><i class="icon-upload"></i> Upload Reports</h4>

                             <div class="d-flex gap-2">
                                 <div class="dropdown">
                                     <select name="location" class="form-select border-primary" required>
                                         <option value="">Location</option>
                                         @foreach (Auth::user()->userable->RetailerAddresses as $address)
                                             <option value="{{ $address->id }}">
                                                 {{ $address->province }}, {{ $address->location }}
                                             </option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="dropdown">
                                     <select id="mySelect" class="form-select border-primary" name="pos" required>
                                         <option class="other" value="">POS</option>
                                         <option class="other" value="cova">Cova</option>
                                         <option id="greenLine" value="greenline">GreenLine</option>
                                         <option value="techpos">TechPOS</option>
                                         <option value="pennylane">Barnet</option>
                                         <option value="ductie">Dutchie</option>
                                         <option value="profittech">ProfitTech</option>
                                         <option value="gobatell">Global Till</option>
                                         <option value="ideal">Ideal</option>
                                         <option value="epos">Other</option>
                                     </select>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="modal-body">
                         <div id="diagnostic_report" class="form-group otherInput">
                             <label id="lable1">Diagnostic Report</label>
                             <input type="file" class="form-control" name="diagnostic_report">
                         </div>

                         <div class="form-group otherInput">
                             <label id="lable2">Sales Summary Report </label>
                             <input type="file" class="form-control" name="sales_summary_report" id="report"
                                 onChange="validate(this.value)" required>
                             <label id="file_error" class="error" for="report_name" style="display: none;">File type must
                                 be
                                 xlsx
                                 or Csv</label>
                         </div>
                         <div class="form-group" style="display: none" id="greenLineInput">
                             <label for="">Inventory Log summary </label>
                             <input type="file" class="form-control" name="inventory_log_summary" required>
                         </div>
                     </div>
                     <div class="modal-footer">
                         <button type="submit" id="submit" class="btn btn-primary">Upload Files</button>
                         <a href="" class="btn" data-bs-dismiss="modal" aria-label="Close">Close</a>
                     </div>
                 </form>
             </div>
         </div>
     </div>
     <script>
         $(document).ready(function() {

             let button = document.querySelector(".button");

             function stateHandle() {
                 if (document.querySelector(".input").value === "") {
                     button.disabled = true; //button remains disabled
                 } else {
                     button.disabled = false; //button is enabled
                 }
             }

             $('#mySelect').change(function() {
                 var value = $(this).val();

                 if (value == 'greenline' || value == 'techpos' || value == 'pennylane' || value ==
                     'profittech') {
                     $('.otherInput').hide();
                     $('#greenLineInput').show();

                 } else if (value == 'other') {
                     $("#lable1").text("File One");
                     $("#lable2").text("File Two");
                 } else if (value == 'ideal') {
                    $("#lable1").text("Finalised Report");
                    $("#lable2").text("Stock Purchase Report");
                } else if (value == 'ductie') {
                     $("#lable1").text("Inventory Receipt Detail");
                     $("#lable2").text("Roll Forward ");
                 } else {
                     $("#lable1").text("Diagnostic Report");
                     $("#lable2").text("Sales Summary Report");
                     $('.otherInput').show();
                     $('#greenLineInput').hide();
                 }
             });
         });
     </script>
 @stop
