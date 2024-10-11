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
    <h1>Email Template</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">New Email Template</li>
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
              <form class="row g-3" action="{{url('/email-template/store')}}" method="post">
              @csrf
              
                <div class="row">

                    <div class="col-12">
                       <h6 class="card-title">Dynamic Block</h6>
                       <code>
                        [lot_number]
                        [lot_name]
                        [lot_sold_price]
                        [brand_name]
                        [seller_name]
                        [seller_email]
                        [lot_highest_bid_price]
                        [here]
                        [attach-pdf]
                        [TodayPlus3days]
                        [bid_place_time]
                        [auction_end_time]
                        [buyer_name]
                      </code>
                    </div>



                    <div class="col-12">
                     <h6 class="card-title">Email Code : <?php echo isset($page['emailtemplate']->id)?'email_temp_'.$page['emailtemplate']->id: ''; ?></h6>
                      <div class="form-floating mb-3">
                        <input type="text" name="note" value="<?php echo isset($page['emailtemplate']->note)?$page['emailtemplate']->note: old('note'); ?>" class="form-control @error('note') is-invalid @enderror" id="floatingNote" placeholder="Note">
                        <label for="floatingNote">Note</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="title" value="<?php echo isset($page['emailtemplate']->title)?$page['emailtemplate']->title: old('title'); ?>" class="form-control @error('title') is-invalid @enderror" id="floatingTitle" placeholder="Title">
                        <label for="floatingTitle">Title</label>
                        @if($errors->has('title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                        @endif
                      </div>
                      <h6 class="card-title">Body</h6>
                      <div class="form-floating" >
                        <textarea class="form-control tinymce-editor @error('body') is-invalid @enderror" name="body" placeholder="Body" id="floatingBody" style="height: 150px;"><?php echo isset($page['emailtemplate']->body)?$page['emailtemplate']->body: old('body'); ?></textarea>
                      </div>
                      @if($errors->has('body'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('body') }}</div>
                        @endif
                    
                      
                    
                    </div>
                  
                </div>

                <div class="text-center">
                  
                  <input type="hidden" name="id" value="<?php echo isset($page['emailtemplate']->id)?$page['emailtemplate']->id: ''; ?>">
                  <button type="submit" class="btn btn-primary">Save Template</button>
                  
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