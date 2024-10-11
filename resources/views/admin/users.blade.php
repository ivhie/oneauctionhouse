@extends('layouts/common-layout')

@section('title', 'Users')

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
    <h1>Users</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->
  </div><!-- End Page Title -->


  <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">User List</h5>
              <!-- Table with stripped rows -->
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  if($page['users']){
                      foreach($page['users'] as $user) {
                       ?>
                         <tr>
                          <td><?php echo $user['id'];?></td>
                          <td><?php echo $user['name'];?></td>
                          <td><?php echo $user['email'];?></td>
                          <td><a href="#" class="btn btn-primary">Edit</a> | <a href="#" class="btn btn-danger">Delete</a></td>
                         </tr>
                       <?php
                      }
                  } else { ?>

                         <tr>
                          <td colspan="4">No record found</td>
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


        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Create User Entry</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="{{url('/users/create')}}" method="post">
              @csrf
                <div class="col-md-6">
                  
                  <div class="form-floating">
                    <input type="text"name="name" class="form-control" id="floatingName" placeholder="Name">
                    <label for="floatingName">Name</label>
                    @error('name')<span class="text-danger">{{$message}}</span>@enderror
                  </div>
                </div>
                <div class="col-md-6">
                  
                  <div class="form-floating">
                    <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email">
                    <label for="floatingEmail">Email</label>  @error('email')<span class="text-danger">{{$message}}</span>@enderror
                  </div>
                </div>
                <div class="col-md-6">
                  
                  <div class="form-floating">
                    <input type="text" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                    @error('password')<span class="text-danger">{{$message}}</span>@enderror
                  </div>
                </div>
               
                <div class="text-center">
                  <input type="hidden" name="user_type"  value="admin">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>


      </div>
    </section>
@endsection
