<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
 <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->

  <title>@yield('title') </title>
  <meta content="" name="description">
  <meta content="" name="keywords">
 <meta http-equiv="Access-Control-Allow-Origin" content="https://oneauctionhouse.com" />
  <!-- Favicons -->
  <link href="{{ url('public/assets/adminstyle/img/favicon.png') }}" rel="icon">
  <link href="{{ url('public/assets/adminstyle/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ url('public/assets/adminstyle/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ url('public/assets/adminstyle/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ url('public/assets/adminstyle/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ url('public/assets/adminstyle/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ url('public/assets/adminstyle/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ url('public/assets/adminstyle/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <!--
  <link href="{{ url('public/assets/adminstyle/vendor/simple-datatables/style.css') }}" rel="stylesheet">-->
  <link rel="stylesheet" href="{{url('public/assets/datatables/jquery.dataTables.min.css')}}">
  <link rel="stylesheet" href="{{url('public/assets/magnific_popup/magnific-popup.css')}}">
  <link rel="stylesheet" href="{{url('public/assets/select2/css/select2.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ url('public/assets/adminstyle/css/style.css') }}" rel="stylesheet">
  

  <script src="{{ url('public/assets/jquery-3.3.1.min.js') }}"></script>
  <!--<script src="http://code.jquery.com/jquery-3.3.1.min.js"></script>-->
  <meta name="_token" content="{{csrf_token()}}" />
  <script>var BASE_URL = '{{url("/")}}';</script>
  
  
      <link href=
'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'
          rel='stylesheet'>
 
    <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
    </script>
 
    <script src=
"https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">
    </script>
  
<!--
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>

<style>
#cke_notifications_area_floatingBiddingDescription { display:none; }
</style>
-->
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

  @include('layouts/header_nav')

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      @include('layouts/sidenav_bar')
   
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    @yield('content')


  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <!--
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>

    <div class="credits">
      Designed by <a href="">test</a>
    </div>
    -->
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ url('public/assets/adminstyle/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ url('public/assets/adminstyle/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('public/assets/adminstyle/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ url('public/assets/adminstyle/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ url('public/assets/adminstyle/vendor/quill/quill.js') }}"></script>
  <!--<script src="{{ url('public/assets/adminstyle/vendor/simple-datatables/simple-datatables.js') }}"></script>-->
   <!-- Added By Ivan Dolera July 2024 -->
  <!-- Datatable -->
  <script src="{{url('public/assets/datatables/jquery.dataTables.min.js')}}"></script>
 <script src="{{ url('public/assets/adminstyle/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('public/assets/adminstyle/vendor/php-email-form/validate.js') }}"></script>
  <!-- Magnifi Pop UP -->
  <script src="{{ url('public/assets/magnific_popup/jquery.magnific-popup.js?v=1.2.0') }}"></script>
  <script src="{{ url('public/assets/select2/js/select2.js') }}"></script>


  <!-- Template Main JS File -->

  <script src="{{ url('public/assets/adminstyle/js/main.js') }}"></script>
 
    @yield('page_script')

</body>

</html>