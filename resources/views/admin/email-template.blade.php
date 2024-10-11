@extends('layouts/common-layout')

@section('title', 'Comments Listing')

@section('vendor-style')

@endsection

@section('vendor-script')

@endsection

@section('page-script')

@endsection

@section('content')
  <div class="pagetitle">
    <h1>Auction Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Email Template</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  <section class="section">
      
    @if (session('failed'))
        <div class="alert">{{ session('failed') }}</div>
    @endif
    @if (session('added'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
          {{ session('added') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
   
     <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body table-responsive">
              <h5 class="card-title">Email Template List</h5>
              <p style="text-align:right;"><a href="{{ url('email-template/new') }}" class="btn btn-primary">Create New</a></p>
              <!-- Table with stripped rows -->
              <table id="email_template_table" class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Email Code</th>
                    <th>Title</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
              <!-- End Table with stripped rows -->
            </div>
          </div>

        </div>
      </div> <!-- row-->


    </section>

 
    
@endsection

@section('page_script')
<!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
<script>
    // display data
    var datatable = jQuery('#email_template_table').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax": BASE_URL+'/email-template/get',
				"order": [[ 0, "desc" ]],
				"createdRow": function( row, data, dataIndex ) {
					  jQuery(row).attr('id', 'template-data-'+ data[0]);
			
					  
				},
				"columnDefs": [ {
								"targets": [2],
								"orderable": false
								} ]
			} );


      /*Delete*/
			jQuery(document).on('click','.btn-delete',function() {
				if ( !confirm('Are you sure you want to delete this?') ) {
					return false;
				}
				
				jQuery.ajaxSetup({
					  headers: {
						  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					  }
				 });
				var id = jQuery(this).data('id');
				button = jQuery(this);
				button.button('loading');
				//pass values to ajax
				jQuery.ajax({
					type: "DELETE",
					dataType: "json",
					url: BASE_URL+'/email-template/delete/'+jQuery(this).data('id'),
					data: {},
					beforeSend: function() {},
					success: function(jasonData) {
						//datatable.ajax.reload();
            //alert(id);
						jQuery("#email_template_table tr#template-data-"+id).remove();
						
					}
				});
			});


    </script>
 @endsection