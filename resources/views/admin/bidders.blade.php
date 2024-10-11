@extends('layouts/common-layout')

@section('title', 'Auction Listing')

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
        <li class="breadcrumb-item active">Auctions</li>
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
              <h5 class="card-title">Bidder&#39;s List</h5>
              <!--<p style="text-align:right;"><a href="{{ url('bidders/new') }}" class="btn btn-primary">Create New</a></p>-->
              <!-- Table with stripped rows -->
               <?php //echo count($page['bidders']['customers']); ?>
              <table id="bidders_table" class="table">
                <thead>
                  <tr>
                    <th>Shopify User ID</th>
                    <th>Name</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Country</th>
                    <th>Created</th>
                    <th>Verified</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(isset($page['bidders_content'])) { 
                    echo $page['bidders_content'];
                    /*
                    for( $j=0; $j<count($page['bidders']['customers']); $j++) { ?>
                                <tr>
                                  <td><?php echo $page['bidders']['customers'][$j]['id']; ?></td>
                                  <td><?php// echo $page['bidders']['customers'][$j]['id']; ?></td>
                                  <td><?php //echo $page['bidders']['customers'][$j]['email']; ?></td>
                                  <td><?php //echo $page['bidders']['customers'][$j]['phone']; ?></td>
                                  <td><?php  echo substr($page['bidders']['customers'][$j]['note'], 7) ; ?></td>
                                  <td><?php  echo $page['bidders']['customers'][$j]['addresses'][0]['country']; ?></td>
                                  <td><?php echo  date('m/d/Y', strtotime($page['bidders']['customers'][$j]['created_at'])) ; ?></td>
                                  <td></td>
                                  <td></td>

                                </tr>
                     <?php
                       
                          }
                     */
                   
                      

                    } ?>
                  
                </tbody>
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
    var datatable = jQuery('#bidders_table').DataTable( {
				//"processing": true,
				//"serverSide": true,
				//"ajax": BASE_URL+'/bidders/get',
				"order": [[ 0, "desc" ]],
				"createdRow": function( row, data, dataIndex ) {
					  jQuery(row).attr('id', 'bidder-data-'+ data[0]);
			
					  
				},
				"columnDefs": [ {
								"targets": [7],
								"orderable": false
								} ]
			} );


      // get records shopify customers customers
      /*
      jQuery.ajax({
					type: "GET",
					//dataType: "json",
					url: BASE_URL+'/bidders/getshopifyusers/',
					data: {},
					beforeSend: function() {},
					success: function(jasonData) {
						//datatable.ajax.reload();
            //alert(id);
            console.log(jasonData);
						jQuery("#bidders_table tbody").html(jasonData);
						
					}
				});
     */

    /*
    var datatable = jQuery('#bidders_table').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax": BASE_URL+'/bidders/get',
				"order": [[ 0, "desc" ]],
				"createdRow": function( row, data, dataIndex ) {
					  jQuery(row).attr('id', 'bidder-data-'+ data[0]);
			
					  
				},
				"columnDefs": [ {
								"targets": [8],
								"orderable": false
								} ]
			} );
      */

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
					url: BASE_URL+'/bidders/delete/'+jQuery(this).data('id'),
					data: {},
					beforeSend: function() {},
					success: function(jasonData) {
						//datatable.ajax.reload();
            //alert(id);
						jQuery("#bidders_table tr#bidder-data-"+id).remove();
						
					}
				});
			});


    </script>
 @endsection