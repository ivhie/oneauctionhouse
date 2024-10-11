@extends('layouts/common-layout')

@section('title', 'Auction Listing')

@section('vendor-style')
<!--<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">-->
@endsection


@section('page-script')
<!--<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>-->
@endsection


@section('content')
  <div class="pagetitle">
    <h1>Bidder&#39;s Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">New Bidder</li>
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
            <div class="card-body">
              <h5 class="card-title"><?php echo $page['subtitle']; ?></h5>
              <!-- Floating Labels Form -->
              <form class="row g-3" action="{{url('/bidders/store')}}" method="post">
              @csrf
              
                <div class="row">

                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="text" readonly name="acct_id" value="<?php echo isset($page['bidder']->acct_id)?$page['bidder']->acct_id: old('acct_id'); ?>" class="form-control @error('acct_id') is-invalid @enderror" id="floatingListingSellerUserName" placeholder="Shopify User ID">
                        <label for="floatingListingSellerUserName">Shopify User ID</label>
                        @if($errors->has('acct_id'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('acct_id') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="name" value="<?php echo isset($page['bidder']->name)?$page['bidder']->name: old('name'); ?>" class="form-control @error('name') is-invalid @enderror" id="floatingListingSellerName" placeholder="Name">
                        <label for="floatingListingSellerName">Name</label>
                        @if($errors->has('name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('name') }}</div>
                        @endif
                      </div>
                     
                      <div class="form-floating mb-3">
                        <input type="text" name="user_name" value="<?php echo isset($page['bidder']->user_name)?$page['bidder']->user_name: old('user_name'); ?>" class="form-control @error('user_name') is-invalid @enderror" id="floatingListingSellerUserName" placeholder="User Name">
                        <label for="floatingListingSellerUserName">User Name</label>
                        @if($errors->has('user_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('user_name') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input <?php echo isset($page['bidder']->email)?'': ''?> type="email" name="email" value="<?php echo isset($page['bidder']->email)?$page['bidder']->email: old('email'); ?>" class="form-control @error('email') is-invalid @enderror" id="floatingListingEmailAddress" placeholder="Email">
                        <label for="floatingListingEmailAddress">Email Address</label>
                        @if($errors->has('email'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
                        @endif
                      </div>
                    
                      <div class="form-floating mb-3">
                      <?php $phone = isset($_REQUEST['phone'])?$_REQUEST['phone']: old('phone'); ?>
                        <input type="text" readonly name="phone" value="<?php echo isset($page['bidder']->phone)?$page['bidder']->phone: $phone; ?>" class="form-control @error('phone') is-invalid @enderror" id="floatingListingSellerPhone" placeholder="Phone Number">
                        <label for="floatingListingSellerPhone">Phone Number</label>
                        @if($errors->has('phone'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('phone') }}</div>
                        @endif
                      </div>
                      <?php $country = isset($_REQUEST['country'])?$_REQUEST['country']: old('country'); ?>
                      <div class="form-floating mb-3">
                        <input type="text" readonly name="country" value="<?php echo isset($page['bidder']->country)?$page['bidder']->country: $country; ?>" class="form-control @error('country') is-invalid @enderror" id="floatingListingSellerCountry" placeholder="Country">
                        <label for="floatingListingSellerCountry">Country</label>
                      </div>
                        

                      <?php 
                          $verified = isset($page['bidder']->verified)?$page['bidder']->verified:''; 
                          //echo $product_status;
                       ?>
                      <div class="form-floating mb-3">
                          <select class="form-select @error('verified') is-invalid @enderror" name="verified" id="floatingWatchBox" aria-label="verified" required>
                            <option value="">-Select-</option>  
                            <option value="yes" <?php echo ($verified == 'yes')?'selected': ''; ?>>Yes</option>
                            <option value="no" <?php echo ($verified == 'no')?'selected': ''; ?>>No</option>
                          </select>
                          <label for="floatingAfterMarket">Verified</label>
                          @if($errors->has('verified'))
                              <div class="invalid-feedback" style="display:block;">{{ $errors->first('verified') }}</div>
                          @endif
                      </div>
                      <h3>Bank Details</h3>
                      <div class="form-floating mb-3">
                        <input type="text" name="pbank_name" value="<?php echo isset($page['bidder']->pbank_name)?$page['bidder']->pbank_name: old('pbank_name'); ?>" class="form-control @error('pbank_name') is-invalid @enderror" id="floatingListingBankName" placeholder="Bank Name">
                        <label for="floatingListingBankName">Bank Name </label>
                        @if($errors->has('pbank_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('pbank_name') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="paccnt_holder_name" value="<?php echo isset($page['bidder']->paccnt_holder_name)?$page['bidder']->paccnt_holder_name: old('paccnt_holder_name'); ?>" class="form-control @error('paccnt_holder_name') is-invalid @enderror" id="floatingListingAccountHolderName" placeholder="Account Holder Name">
                        <label for="floatingListingAccountHolderName">Account Holder Name</label>
                        @if($errors->has('paccnt_holder_name'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('paccnt_holder_name') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="paccnt_number" value="<?php echo isset($page['bidder']->paccnt_number)?$page['bidder']->paccnt_number: old('paccnt_number'); ?>" class="form-control @error('paccnt_number') is-invalid @enderror" id="floatingListingAccountNumber" placeholder="Account Number">
                        <label for="floatingListingAccountNumber">Account Number</label>
                        @if($errors->has('paccnt_number'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('paccnt_number') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="prouting_number" value="<?php echo isset($page['bidder']->prouting_number)?$page['bidder']->paccnt_number: old('prouting_number'); ?>" class="form-control @error('prouting_number') is-invalid @enderror" id="floatingListingRoutingNumber" placeholder="Routing Number (ABA)">
                        <label for="floatingListingRoutingNumber">Routing Number (ABA)</label>
                        @if($errors->has('prouting_number'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('prouting_number') }}</div>
                        @endif
                      </div>

                      <div class="form-floating mb-3">
                        <input type="text" name="pswift_code" value="<?php echo isset($page['bidder']->pswift_code)?$page['bidder']->pswift_code: old('pswift_code'); ?>" class="form-control @error('pswift_code') is-invalid @enderror" id="floatingListingswiftcode" placeholder="SWIFT Code (for international transfers)">
                        <label for="floatingListingswiftcode">SWIFT Code (for international transfers)</label>
                        @if($errors->has('pswift_code'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('pswift_code') }}</div>
                        @endif
                      </div>

                      <div class="form-floating mb-3">
                        <input type="text" name="pbank_address" value="<?php echo isset($page['bidder']->pbank_address)?$page['bidder']->pbank_address: old('pbank_address'); ?>" class="form-control @error('pbank_address') is-invalid @enderror" id="floatingListingbankaddress" placeholder="Bank Address">
                        <label for="floatingListingbankaddress">Bank Address</label>
                        @if($errors->has('pbank_address'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('pbank_address') }}</div>
                        @endif
                      </div>





                    </div>
                  
                </div>

                <div class="text-center">
                  <input type="hidden" name="submitedfrom" value="laravel">
                  <input type="hidden" name="id" value="<?php echo isset($page['bidder']->id)?$page['bidder']->id: ''; ?>">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <!--<button type="reset" class="btn btn-secondary">Reset</button>-->
                </div>
              </form><!-- End floating Labels Form -->
            </div>
          </div>
        </div>
      </div>

    </section>

 
    
@endsection

@section('page_script')


 @endsection