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
                             <li class="breadcrumb-item active">Clean Report</li>
                         </ol>
                     </div>
                     <div class="col-auto">
                         <div class="page-title-right">
                             <div class="col flex-align gap-30">
                                 <form action="" method="POST">
                                    @csrf
                                     <div class="input-group">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="retailer_id" value="{{ $retailer_id }}">
                                         <input type="text" class="form-control" placeholder="Search" name="dirty_row"
                                             required="">
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
             <br>
             <section class="section">
                 <div class="container">
                     <div class="content-box listing-box">
                         <h4 class="content-title">Clean Report</h4>
                         <div class="table-responsive pb-3">
                             <table class="table mb-0">
                                 <thead>
                                     <tr>
                                         <th>Sr.</th>
                                         <th>Sku</th>
                                         <th>Product Name</th>
                                         <th>Category</th>
                                         <th>Brand</th>
                                         <th>Items Sold</th>
                                         <th>Items Purchased</th>
                                         <th>Barcode</th>
                                         <th>Average Price</th>
                                         <th>Average Cost</th>
                                         <th>Comments</th>
                                         <th style="text-align: center;">Action</th>
                                     </tr>
                                 </thead>

                                 <tbody>
                                     @foreach ($dirtyRows as $dirtyRow)
                                         <tr>
                                             <td>{{ $loop->index + 1 }}</td>
                                             <td>{{ $dirtyRow->sku }}</td>
                                             <td>{{ $dirtyRow->product_name }}</td>
                                             <td>{{ $dirtyRow->category }}</td>
                                             <td>{{ $dirtyRow->brand }}</td>
                                             <td>{{ $dirtyRow->sold }}</td>
                                             <td>{{ $dirtyRow->purchased }}</td>
                                             <td>{{ $dirtyRow->barcode }}</td>
                                             <td>{{ $dirtyRow->average_price }}</td>
                                             <td>{{ $dirtyRow->average_cost }}</td>
                                             <td>{{ $dirtyRow->comments }}</td>
                                             <td>
                                                 <div class="dropdown">
                                                     <a href="{{ route('dirty.rows.destroy', $dirtyRow->id) }}"
                                                         class="view-box">
                                                         <i class="fa fa-trash"></i>
                                                     </a>
                                                     <a href="{{ route('dirty.rows.edit', $dirtyRow->id) }}"
                                                         class="view-box mt-1">
                                                         <i class="fa fa-edit"></i>
                                                     </a>
                                                 </div>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         </div>
                         {!! $dirtyRows->links() !!}
                     </div>
                 </div>
             </section>
     </main>
 @stop
