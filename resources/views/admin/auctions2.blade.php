@extends('layouts/common-layout')

@section('title')
<?php echo $page['page_title'];?>
@endsection
@section('vendor-style')
<!--<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">-->
@endsection

@section('vendor-script')
<!--<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>-->
@endsection

@section('page-script')
<!--<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>-->
@endsection

@section('content')
<div class="pagetitle">
    <h1><?php echo $page['page_title'];?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Auctions</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->



  <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $page['page_title'];?></h5>
              <!--<p style="text-align:right;"><a href="#" class="btn btn-primary">Create New</a></p>-->
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Priority</th>
                    <th>Lot Number</th>
                    <th>Item Title</th>
                    <th>Bids</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Last Updated</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  if(0){
                      foreach($page['auctions'] as $auction) {
                       ?>
                         <tr>
                          <td><?php echo $auction['id'];?></td>
                          <td><?php
                          //var_dump($auction['images'][0]['src']);
                          echo isset($auction['images'][0]['src'])?'<img src="'.$auction['images'][0]['src'].'" alt="" width="100px" height="100px;" />':'No Image' ;?></td>
                          <td><?php echo $auction['title'];?></td>
                          <td><?php echo $auction['vendor'];?></td>
                          <td><?php echo $auction['product_type'];?></td>
                          <td><?php echo $auction['status'];?></td>
                         </tr>
                       <?php
                      }
                  } else { ?>

                         <tr>
                          <td colspan="9">No record found</td>
                         </tr>

                 <?php
                  }
                ?>
                  
                 
                 
                 
                  
                  
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>



   
      <div class="row">
        <div class="col-lg-12">


        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Auction Entry</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="{{url('auction-create')}}" method="post">
              @csrf
                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text"name="title" class="form-control" id="floatingListingName" placeholder="Listing Name">
                    <label for="floatingListingName">Listing Name</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating">
                    <textarea class="form-control" name="description" placeholder="Description" id="floatingDescription" style="height: 100px;"></textarea>
                    <label for="floatingDescription">Description</label>
                  </div>
                </div>



                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" name="vendor" class="form-control" id="floatingVendor" placeholder="Vendor">
                    <label for="floatingVendor">Vendor</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="productType" id="floatingProductType" placeholder="Product Type">
                    <label for="floatingProductType">Product Type</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" name="tags" id="floatingTags" placeholder="Tags">
                    <label for="floatingTags">Tags</label>
                  </div>
                </div>
                <!--
                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="floatingStatus" aria-label="Status">
                      <option selected>Status</option>
                      <option value="draft">Draft</option>
                      <option value="active">Active</option>
                    </select>
                    <label for="floatingStatus">Status</label>
                  </div>
                </div>
                -->
                <!--
                <div class="col-md-6">
                  <div class="col-md-12">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="floatingCity" placeholder="City">
                      <label for="floatingCity">City</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="floatingSelect" aria-label="State">
                      <option selected>New York</option>
                      <option value="1">Oregon</option>
                      <option value="2">DC</option>
                    </select>
                    <label for="floatingSelect">State</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingZip" placeholder="Zip">
                    <label for="floatingZip">Zip</label>
                  </div>
                </div>
                -->
                <div class="text-center">
          
              
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>


      </div>

      </div>
          </div>


      </div>
    </section>
@endsection
