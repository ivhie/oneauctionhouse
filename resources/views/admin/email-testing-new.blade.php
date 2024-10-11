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
    <h1>Email Testing</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">New Test Email</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


  <section class="section">
      
    @if (session('failed'))
        <div class="alert">{{ session('failed') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
   
  

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $page['subtitle']; ?></h5>
              <!-- Floating Labels Form -->
              <form class="row g-3" action="{{ url('/email-template-test/send') }}" method="post">
              @csrf
              
                <div class="row">

                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="text" name="send_to" value="ivandolera24@gmail.com" class="form-control @error('send_to') is-invalid @enderror" id="floatingNote" placeholder="Send To">
                        <label for="floatingNote">Send To</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="title" value="Welcome to one auction house" class="form-control @error('title') is-invalid @enderror" id="floatingTitle" placeholder="Title">
                        <label for="floatingTitle">Title</label>
                        @if($errors->has('title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('title') }}</div>
                        @endif
                      </div>
                      <h6 class="card-title">Body</h6>
                      <div class="form-floating" >
                        <textarea class="form-control tinymce-editor @error('body') is-invalid @enderror" name="body" placeholder="Body" id="floatingBody" style="height: 150px;">Welcome Body Test from One ouction house</textarea>
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