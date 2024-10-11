@extends('layouts/common-layout')

@section('title', 'Comment Listing')

@section('vendor-style')
<!--<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">-->
@endsection


@section('page-script')
<!--<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>-->
@endsection


@section('content')
  <div class="pagetitle">
    <h1>Comment Listing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">New Comment</li>
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
              <form class="row g-3" action="{{url('/comments/store')}}" method="post">
              @csrf
              
                <div class="row">

                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="text" name="acct_id" value="<?php echo isset($page['comment']->acct_id)?$page['comment']->acct_id: old('acct_id'); ?>" class="form-control @error('acct_id') is-invalid @enderror" id="floatingListingSellerUserName" placeholder="Shopify User ID">
                        <label for="floatingListingSellerUserName">Shopify User ID</label>
                        @if($errors->has('acct_id'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('acct_id') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="lot_id" value="<?php echo isset($page['comment']->lot_id)?$page['comment']->lot_id: old('lot_id'); ?>" class="form-control @error('lot_id') is-invalid @enderror" id="floatingListingSellerName" placeholder="Lot number">
                        <label for="floatingListingSellerName">Lot Number</label>
                        @if($errors->has('lot_id'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('lot_id') }}</div>
                        @endif
                      </div>
                     
                      <div class="form-floating mb-3">
                        <input type="text" name="comment" value="<?php echo isset($page['comment']->comment)?$page['comment']->comment: old('comment'); ?>" class="form-control @error('bid_amt') is-invalid @enderror" id="floatingListingSellerUserName" placeholder="Comments">
                        <label for="floatingListingSellerUserName">Comment</label>
                        @if($errors->has('comment'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('comment') }}</div>
                        @endif
                      </div>
                    </div>
                  
                </div>

                <div class="text-center">
                  <input type="hidden" name="submitedfrom" value="laravel">
                  <input type="hidden" name="id" value="<?php echo isset($page['comment']->id)?$page['comment']->id: ''; ?>">
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