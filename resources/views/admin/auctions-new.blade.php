@extends('layouts/common-layout')

@section('title', $page['page_title'])

@section('vendor-style')
<!--<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">-->
@endsection


@section('page-script')
<!--<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>-->
@endsection

<style>
.select2-container .select2-selection--single,
.select2-container--default .select2-selection--single { height:56px !important; }
.select2-container--default .select2-selection--single .select2-selection__rendered {
    margin-top: 23px !important;
}
</style>
@section('content')
  <div class="pagetitle">
    <h1><?php echo $page['page_title']; ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">New Auctions</li>
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
              <form class="row g-3" action="{{url('auction-post-lara-db')}}" method="post" enctype="multipart/form-data">
              @csrf
                <h3>LOT NUMBER : #<?php echo isset($page['auction']->watch_lot_id)?$page['auction']->watch_lot_id: ''; ?> </h3>
                <div class="row">
                    <div class="col-md-4">
                      
                      

                      <h6 class="card-title">Watch Details</h6>
                      <div class="form-floating mb-3">
                       <?php 
                          $product_status = isset($page['auction']->product_status)?$page['auction']->product_status:''; 
                          //echo $product_status;
                       ?>
                        <select class="form-select @error('product_status') is-invalid @enderror" id="floatingStatus" name="product_status" aria-label="Package" required>
                          <option value="pending" <?php echo ( $product_status == 'pending')?'selected': ''; ?>>Pending</option>
                          <option value="live" <?php echo ( $product_status == 'live')?'selected': ''; ?>>Live</option>
                          <option value="rejected" <?php echo ( $product_status == 'rejected')?'selected': ''; ?>>Rejected</option>
                          <option value="completed" <?php echo ( $product_status == 'completed')?'selected': ''; ?>>Completed</option>
                          <option value="post" <?php echo ( $product_status == 'post')?'selected': ''; ?>>Post</option>
                        </select>
                        <label for="floatingStatus">Status</label>
                        @if($errors->has('product_status'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('product_status') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <!--
                        <label for="floatingListingBrand">Brand</label>-->
                        <?php 
                          $watch_brand = isset($page['auction']->watch_brand)?$page['auction']->watch_brand:''; 
                          //echo $product_status;
                       ?>
                      <select  name="watch_brand" class="watch_brand form-select @error('watch_brand') is-invalid @enderror" id="watch_brand">
                        <option value="">Select Brand</option>
                        <option value="Alain Silberstein" <?php echo ($watch_brand == 'Alain Silberstein')?'selected': ''; ?>>Alain Silberstein</option>
                        <option value="A. Lange & Söhne" <?php echo ($watch_brand == 'A. Lange & Söhne')?'selected': ''; ?>>A. Lange & Söhne</option>             
                        <option value="Arnold & Son" <?php echo ($watch_brand == 'Arnold & Son')?'selected': ''; ?>>Arnold & Son</option>               
                        <option value="Audemars Piguet" <?php echo ($watch_brand == 'Audemars Piguet')?'selected': ''; ?>>Audemars Piguet</option>          
                        <option value="Azimuth" <?php echo ($watch_brand == 'Azimuth')?'selected': ''; ?>>Azimuth</option>           
                        <option value="Ball Fireman" <?php echo ($watch_brand == 'Ball Fireman')?'selected': ''; ?>>Ball Fireman</option>        
                        <option value="Bamford" <?php echo ($watch_brand == 'Bamford')?'selected': ''; ?>>Bamford</option>   
                        <option value="Baume & Mercier" <?php echo ($watch_brand == 'Baume & Mercier')?'selected': ''; ?>>Baume & Mercier</option>               
                        <option value="Bedat & Co" <?php echo ($watch_brand == 'Bedat & Co')?'selected': ''; ?>>Bedat & Co</option>               
                        <option value="Behrens" <?php echo ($watch_brand == 'Behrens')?'selected': ''; ?>>Behrens</option>               
                        <option value="Bell & Ross" <?php echo ($watch_brand == 'Bell & Ross')?'selected': ''; ?>>Bell & Ross</option>       
                        <option value="Blancpain" <?php echo ($watch_brand == 'Blancpain')?'selected': ''; ?>>Blancpain</option>               
                        <option value="Bovet" <?php echo ($watch_brand == 'Bovet')?'selected': ''; ?>>Bovet</option>                   
                        <option value="Breguet" <?php echo ($watch_brand == 'Breguet')?'selected': ''; ?>>Breguet</option>                 
                        <option value="Breitling" <?php echo ($watch_brand == 'Breitling')?'selected': ''; ?>>Breitling</option>           
                        <option value="Bremont" <?php echo ($watch_brand == 'Bremont')?'selected': ''; ?>>Bremont</option>               
                        <option value="Bvlgari" <?php echo ($watch_brand == 'Bvlgari')?'selected': ''; ?>>Bvlgari</option>               
                        <option value="Carl F. Bucherer" <?php echo ($watch_brand == 'Carl F. Bucherer')?'selected': ''; ?>>Carl F. Bucherer</option>             
                        <option value="Cartier" <?php echo ($watch_brand == 'Cartier')?'selected': ''; ?>>Cartier</option>                
                        <option value="Chanel" <?php echo ($watch_brand == 'Chanel')?'selected': ''; ?>>Chanel</option>                 
                        <option value="Chopard" <?php echo ($watch_brand == 'Chopard')?'selected': ''; ?>>Chopard</option>             
                        <option value="Christophe Claret" <?php echo ($watch_brand == 'Christophe Claret')?'selected': ''; ?>>Christophe Claret</option>           
                        <option value="Chronoswiss" <?php echo ($watch_brand == 'Chronoswiss')?'selected': ''; ?>>Chronoswiss</option>         
                        <option value="Corum" <?php echo ($watch_brand == 'Corum')?'selected': ''; ?>>Corum</option>              
                        <option value="Czapek & Cie" <?php echo ($watch_brand == 'Czapek & Cie')?'selected': ''; ?>>Czapek & Cie</option>             
                        <option value="De Grisogono" <?php echo ($watch_brand == 'De Grisogono')?'selected': ''; ?>>De Grisogono</option>             
                        <option value="DeWitt" <?php echo ($watch_brand == 'DeWitt')?'selected': ''; ?>>DeWitt</option>                  
                        <option value="Ferdinand Berthoud" <?php echo ($watch_brand == 'Ferdinand Berthoud')?'selected': ''; ?>>Ferdinand Berthoud</option>                
                        <option value="F.P. Journe" <?php echo ($watch_brand == 'F.P. Journe')?'selected': ''; ?>>F.P. Journe</option>           
                        <option value="Franck Muller" <?php echo ($watch_brand == 'Franck Muller')?'selected': ''; ?>>Franck Muller</option>              
                        <option value="Franc Vila" <?php echo ($watch_brand === 'Franc Vila')?'selected': ''; ?>>Franc Vila</option>         
                        <option value="Frederique Constant" <?php echo ($watch_brand == 'Frederique Constant')?'selected': ''; ?>>Frederique Constant</option>              
                        <option value="George Daniels" <?php echo ($watch_brand == 'George Daniels')?'selected': ''; ?>>George Daniels</option>                 
                        <option value="Girard Perregaux" <?php echo ($watch_brand == 'Girard Perregaux')?'selected': ''; ?>>Girard Perregaux</option>                  
                        <option value="Glashütte Original" <?php echo ($watch_brand == 'Glashütte Original')?'selected': ''; ?>>Glashütte Original</option>                
                        <option value="Graham" <?php echo ($watch_brand == 'Graham')?'selected': ''; ?>>Graham</option>                 
                        <option value="Grand Seiko" <?php echo ($watch_brand == 'Grand Seiko')?'selected': ''; ?>>Grand Seiko</option>                   
                        <option value="Greubel Forsey" <?php echo ($watch_brand == 'Greubel Forsey')?'selected': ''; ?>>Greubel Forsey</option>                  
                        <option value="Hamilton" <?php echo ($watch_brand == 'Hamilton')?'selected': ''; ?>>Hamilton</option>                  
                        <option value="Harry Winston" <?php echo ($watch_brand == 'Yes')?'selected': ''; ?>>Harry Winston</option>                  
                        <option value="Haut lence" <?php echo ($watch_brand == 'Haut lence')?'selected': ''; ?>>Haut lence</option>                   
                        <option value="Hermès" <?php echo ($watch_brand == 'Hermès')?'selected': ''; ?>>Hermès</option>                    
                        <option value="H. Moser & Cie" <?php echo ($watch_brand == 'H. Moser & Cie')?'selected': ''; ?>>H. Moser & Cie</option>                   
                        <option value="Hublot" <?php echo ($watch_brand == 'Hublot')?'selected': ''; ?>>Hublot</option>           
                        <option value="IWC Schaffhausen" <?php echo ($watch_brand == 'IWC Schaffhausen')?'selected': ''; ?>>IWC Schaffhausen</option>            
                        <option value="Jacob & Co." <?php echo ($watch_brand == 'Jacob & Co.')?'selected': ''; ?>>Jacob & Co.</option>            
                        <option value="Jaeger-LeCoultre" <?php echo ($watch_brand == 'Jaeger-LeCoultre')?'selected': ''; ?>>Jaeger-LeCoultre</option>            
                        <option value="Jaquet Droz" <?php echo ($watch_brand == 'Jaquet Droz')?'selected': ''; ?>>Jaquet Droz</option>          
                        <option value="Laurent Ferrier" <?php echo ($watch_brand == 'Laurent Ferrier')?'selected': ''; ?>>Laurent Ferrier</option>          
                        <option value="Linde Werdelin" <?php echo ($watch_brand == 'Linde Werdelin')?'selected': ''; ?>>Linde Werdelin</option>          
                        <option value="Louis Moinet" <?php echo ($watch_brand == 'Louis Moinet')?'selected': ''; ?>>Louis Moinet</option>             
                        <option value="Maitres Du Temps" <?php echo ($watch_brand == 'Maitres Du Temps')?'selected': ''; ?>>Maitres Du Temps</option>              
                        <option value="Maurice Lacroix" <?php echo ($watch_brand == 'Maurice Lacroix')?'selected': ''; ?>>Maurice Lacroix</option>           
                        <option value="MB&F" <?php echo ($watch_brand == 'MB&F')?'selected': ''; ?>>MB&F</option>              
                        <option value="MeisterSinger" <?php echo ($watch_brand == 'MeisterSinger')?'selected': ''; ?>>MeisterSinger</option>         
                        <option value="Montblanc" <?php echo ($watch_brand == 'Montblanc')?'selected': ''; ?>>Montblanc</option>     
                        <option value="Moritz Grossmann" <?php echo ($watch_brand == 'Moritz Grossmann')?'selected': ''; ?>>Moritz Grossmann</option>       
                        <option value="NOMOS Glashutte" <?php echo ($watch_brand == 'NOMOS Glashutte')?'selected': ''; ?>>NOMOS Glashutte</option>             
                        <option value="Omega" <?php echo ($watch_brand == 'Omega')?'selected': ''; ?>>Omega</option>                 
                        <option value="Oris" <?php echo ($watch_brand == 'Oris')?'selected': ''; ?>>Oris</option>            
                        <option value="Panerai" <?php echo ($watch_brand == 'Panerai')?'selected': ''; ?>>Panerai</option>                 
                        <option value="Parmigiani Fleurier" <?php echo ($watch_brand == 'Parmigiani Fleurier')?'selected': ''; ?>>Parmigiani Fleurier</option>          
                        <option value="Patek Philippe" <?php echo ($watch_brand == 'Patek Philippe')?'selected': ''; ?>>Patek Philippe</option>      
                        <option value="Piaget" <?php echo ($watch_brand == 'Piaget')?'selected': ''; ?>>Piaget</option>         
                        <option value="Porsche Design" <?php echo ($watch_brand == 'Porsche Design')?'selected': ''; ?>>Porsche Design</option>           
                        <option value="Rado" <?php echo ($watch_brand == 'Rado')?'selected': ''; ?>>Rado</option>    
                        <option value="Raymond Weil" <?php echo ($watch_brand == 'Raymond Weil')?'selected': ''; ?>>Raymond Weil</option>    
                        <option value="Ressence" <?php echo ($watch_brand == 'Ressence')?'selected': ''; ?>>Ressence</option>       
                        <option value="Richard Mille" <?php echo ($watch_brand == 'Richard Mille')?'selected': ''; ?>>Richard Mille</option>                
                        <option value="Roberto Cavalli" <?php echo ($watch_brand == 'Roberto Cavalli')?'selected': ''; ?>>Roberto Cavalli</option>                
                        <option value="Roger Dubuis" <?php echo ($watch_brand == 'Roger Dubuis')?'selected': ''; ?>>Roger Dubuis</option>                
                        <option value="Roger Smith" <?php echo ($watch_brand == 'Roger Smith')?'selected': ''; ?>>Roger Smith</option>                
                        <option value="Rolex" <?php echo ($watch_brand == 'Rolex')?'selected': ''; ?>>Rolex</option>                  
                        <option value="Romain Gauthier" <?php echo ($watch_brand == 'Romain Gauthier')?'selected': ''; ?>>Romain Gauthier</option>                  
                        <option value="Stührling" <?php echo ($watch_brand == 'Stührling')?'selected': ''; ?>>Stührling</option>                
                        <option value="Tag Heuer" <?php echo ($watch_brand == 'Tag Heuer')?'selected': ''; ?>>Tag Heuer</option>                         
                        <option value="Tiffany & Co." <?php echo ($watch_brand == 'Tiffany & Co.')?'selected': ''; ?>>Tiffany & Co.</option>                           
                        <option value="Tonino Lamborghini" <?php echo ($watch_brand == 'Tonino Lamborghini')?'selected': ''; ?>>Tonino Lamborghini</option>           
                        <option value="Tudor" <?php echo ($watch_brand == 'Tudor')?'selected': ''; ?>>Tudor</option>               
                        <option value="U-Boat" <?php echo ($watch_brand == 'U-Boat')?'selected': ''; ?>>U-Boat</option>                 
                        <option value="Ulysse Nardin" <?php echo ($watch_brand == 'Ulysse Nardin')?'selected': ''; ?>>Ulysse Nardin</option>               
                        <option value="Urwerk" <?php echo ($watch_brand == 'Urwerk')?'selected': ''; ?>>Urwerk</option>                 
                        <option value="Vacheron Constantin" <?php echo ($watch_brand == 'Vacheron Constantin')?'selected': ''; ?>>Vacheron Constantin</option>          
                        <option value="Vianney Halter" <?php echo ($watch_brand == 'Vianney Halter')?'selected': ''; ?>>Vianney Halter</option>               
                        <option value="Zenith" <?php echo ($watch_brand == 'Zenith')?'selected': ''; ?>>Zenith</option>
                      </select>
                      <label for="floatingPackage">Brand</label>


                        @if($errors->has('watch_brand'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_brand') }}</div>
                        @endif
                      </div>

                      <div class="form-floating mb-3">
                    
                        <input type="text" name="watch_model_number" value="<?php echo isset($page['auction']->watch_model_number)?$page['auction']->watch_model_number: old('watch_model_number'); ?>" class="form-control @error('watch_model_number') is-invalid @enderror" id="floatingListingModel" placeholder="Model Number">
                        <label for="floatingListingModel">Model Number</label>
                        @if($errors->has('watch_model_number'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_model_number') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="year_of_watch" value="<?php echo isset($page['auction']->year_of_watch)?$page['auction']->year_of_watch: old('year_of_watch'); ?>" class="form-control @error('year_of_watch') is-invalid @enderror" id="floatingListingYear" placeholder="Year of Watch" required>
                        <label for="floatingListingYear">Year of Watch</label>
                        @if($errors->has('year_of_watch'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('year_of_watch') }}</div>
                        @endif
                      </div>
                     
                      <?php 
                          $watch_package = isset($page['auction']->watch_package)?$page['auction']->watch_package:''; 
                          //echo $product_status;
                       ?>
                      <div class="form-floating mb-3">
                        <select class="form-select @error('watch_package') is-invalid @enderror" id="floatingPackage" name="watch_package" aria-label="Package" required>
                          <option value="">Package</option>
                          <option value="No Reserve Auction" <?php echo ($watch_package == 'No Reserve Auction')?'selected': ''; ?>>No Reserve Auction</option>
                          <option value="Yes Reserve Auction" <?php echo ($watch_package == 'Yes Reserve Auction')?'selected': ''; ?>>Yes Reserve Auction</option>
                        </select>
                        <label for="floatingPackage">Package</label>
                        @if($errors->has('selected'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('selected') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="watch_reserve" value="<?php echo isset($page['auction']->reserves)?$page['auction']->reserves: old('watch_reserve'); ?>" class="form-control @error('watch_reserve') is-invalid @enderror" id="floatingListingwatch_reserve" placeholder="Reserve">
                        <label for="floatingListingModel">Reserve</label>
                        @if($errors->has('watch_reserve'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_reserve') }}</div>
                        @endif
                      </div>
                      <h6 class="card-title">Documentation</h6>
                      <?php 
                          $watch_papers = isset($page['auction']->watch_papers)?$page['auction']->watch_papers:''; 
                          //echo $product_status;
                       ?>
                      <div class="form-floating mb-3">
                        <select class="form-select @error('watch_papers') is-invalid @enderror" name="watch_papers" id="floatingWatchPapers" aria-label="WatchPapers" required>
                            <option value="">Do you have the watch papers?</option>
                            <option value="1" <?php echo ($watch_papers == '1')?'selected': ''; ?>>- Yes, Papers</option>
                            <option value="2" <?php echo ($watch_papers == '2')?'selected': ''; ?>>- Yes, Open Papers (not stamped by the AD)</option>
                            <option value="3" <?php echo ($watch_papers == '3')?'selected': ''; ?>>-I don't know, I'll send photos</option>
                        
                        </select>
                        <label for="floatingPackage">Do you have the watch papers?</label>
                        @if($errors->has('watch_papers'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_papers') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                      <?php 
                          $watch_box = isset($page['auction']->watch_box)?$page['auction']->watch_box:''; 
                          //echo $product_status;
                       ?>
                        <select class="form-select @error('watch_box') is-invalid @enderror" name="watch_box" id="floatingWatchBox" aria-label="WatchBox" required>
                            <option value="yes" <?php echo ($watch_box == 'yes')?'selected': ''; ?>>Yes</option>
                            <option value="no" <?php echo ($watch_box == 'no')?'selected': ''; ?>>No</option>
                        </select>
                        <label for="WatchBox">Box</label>
                        @if($errors->has('watch_box'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_box') }}</div>
                        @endif
                      </div>
                      <h6 class="card-title">Aftermarket Components</h6>
                      <div class="form-floating mb-3">
                      <?php 
                          $watch_after_market = isset($page['auction']->watch_after_market)?$page['auction']->watch_after_market:''; 
                          //echo $product_status;
                       ?>
                        <!--<input type="text" name="watch_after_market" value="<?php echo isset($page['auction']->watch_after_market)?$page['auction']->watch_after_market: old('watch_after_market'); ?>" class="form-control @error('watch_after_market') is-invalid @enderror" id="floatingAfterMarket" placeholder="Aftermarket Components">-->
                        <select class="form-select @error('watch_after_market') is-invalid @enderror" name="watch_after_market" id="floatingWatchBox" aria-label="watch_after_market" required>
                            <option value="Yes" <?php echo ($watch_after_market == 'Yes')?'selected': ''; ?>>Yes</option>
                            <option value="No" <?php echo ($watch_after_market == 'No')?'selected': ''; ?>>No</option>
                            <option value="I don&#39;t know" <?php echo ($watch_after_market == 'I don&#39;t know')?'selected': ''; ?>>I don&#39;t know</option>
                        </select>
                        
                        <label for="floatingAfterMarket">Any non-original parts?</label>
                        @if($errors->has('watch_after_market'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_after_market') }}</div>
                        @endif
                      </div>
                      
                      <p>Any noticeable scratches, nicks, or damage? If yes, describe.</p>
                      <div class="form-floating mb-3">
                          <input type="text" name="watch_condition" value="<?php echo isset($page['auction']->watch_condition)?$page['auction']->watch_condition: old('watch_condition'); ?>" class="form-control @error('watch_condition') is-invalid @enderror" id="floatingAfterMarket" placeholder="">
                      <?php 
                         // $watch_condition = isset($page['auction']->watch_condition)?$page['auction']->watch_condition:''; 
                          //echo $product_status;
                       ?>
                       <!--
                        <select class="form-select @error('watch_condition') is-invalid @enderror" name="watch_condition" id="floatingCondition" aria-label="Condition" required>
                            <option value="New" <?php  //echo ($watch_condition == 'New')?'selected': ''; ?>>New</option>
                            <option value="Used" <?php //echo ($watch_condition == 'Used')?'selected': ''; ?>>Used</option>
                        </select>
                        -->
                        <label for="floatingCondition">Condition</label>
                        @if($errors->has('watch_condition'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('watch_condition') }}</div>
                        @endif
                      </div>


                    </div>

                    <div class="col-4">
                      
                      <h6 class="card-title">Auction Details</h6>
                      <div class="form-floating mb-3">
                        <input type="date" name="bidding_date" value="<?php echo isset($page['auction']->bidding_date)?$page['auction']->bidding_date: old('bidding_date'); ?>" class="form-control @error('bidding_date') is-invalid @enderror" id="floatingListingBiddingDate" placeholder="10/07/2024" required />
                        <label for="floatingListingBiddingDate">Schedule Bid End Date (10/07/2024)</label>
                        @if($errors->has('bidding_date'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('bidding_date') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="time" name="bidding_time" value="<?php echo isset($page['auction']->bidding_time)?$page['auction']->bidding_time: old('bidding_time'); ?>" class="form-control @error('bidding_time') is-invalid @enderror" id="floatingListingBiddingTime" placeholder="12:30PM" required />
                        <label for="floatingListingBiddingTime">Schedule Bid End Time (12:30PM)</label>
                        @if($errors->has('bidding_time'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('bidding_time') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" name="bidding_title" value="<?php echo isset($page['auction']->bidding_title)?$page['auction']->bidding_title: old('bidding_title'); ?>" class="form-control @error('bidding_title') is-invalid @enderror" id="floatingListingBiddingTitle" placeholder="Title" required />
                        <label for="floatingListingBiddingTitle">Title</label>
                        @if($errors->has('bidding_title'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('bidding_title') }}</div>
                        @endif
                      </div>
                      <div class="form-floating">
                        <textarea class="form-control @error('bidding_description') is-invalid @enderror" name="bidding_description" placeholder="Bidding Description" id="floatingBiddingDescription" style="height: 150px;"><?php echo isset($page['auction']->bidding_description)?$page['auction']->bidding_description: old('bidding_description'); ?></textarea>
                        <label for="floatingBiddingDescription">Description</label>
                       
                      </div>
                    
                  
                      <h6 class="card-title">Gallery</h6>
                      <div class="form-row mb-3">
                        <label>Watch Photos</label>
                        <input type="file" name="watch_photos[]" value="dd" id="watch_photos" multiple class="form-control mb-3  @error('watch_photos') is-invalid @enderror" accept='image/*'>
                      </div>
                      
                      
                     <?php  if( isset($page['watch_photos_urls']) ) { 
                            //var_dump($page['auction']->watch_photos);
                            //$image_urls = explode(',', $page['auction']->watch_photos);
                           
                            //var_dump($image_urls);
                       ?>
                          <p><a href="<?php echo url('/auctions/download/'.$page['auction']->watch_lot_id); ?>">Download All Images</a></p>
                          <div class="html-code grid-of-images">
                            <div class="popup-gallery">
                              <?php foreach($page['watch_photos_urls'] as $url ){ 
                                 //$url = Storage::disk('public')->url($url);
                                ?>
                                  <a href="<?php echo $url ?>" title="LOT NUMBER : #<?php echo isset($page['auction']->watch_lot_id)?$page['auction']->watch_lot_id: ''; ?>"><img src="<?php echo $url ?>" width="75" height="75" alt="" /></a>
                              <?php } ?>
                            </div>
                          </div>
                          <script type="text/javascript">
                            $(document).ready(function() {
                              $('.popup-gallery').magnificPopup({
                                delegate: 'a',
                                type: 'image',
                                tLoading: 'Loading image #%curr%...',
                                mainClass: 'mfp-img-mobile',
                                gallery: {
                                  enabled: true,
                                  navigateByImgClick: true,
                                  preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                                },
                                image: {
                                  tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                                  titleSrc: function(item) {
                                    return item.el.attr('title');
                                  }
                                }
                              });
                            });
                          </script>
                      <?php } ?>
                      <!--
                      <div class="form-row mb-3">
                        <label>Watch Photos with notable damages/scratch</label>
                        <input type="file" name="watch_photos_notable_damage[]" id="watch_photos_notable_damage" multiple class="form-control mb-3  @error('watch_photos_notable_damage') is-invalid @enderror">
                      </div>
                      <div class="form-row mb-3">
                        <label>Watch Photos with accessories</label>
                        <input type="file" name="watch_photos_accessories[]" id="watch_photos_accessories" multiple class="form-control mb-3  @error('watch_photos_accessories') is-invalid @enderror">
                      </div>
                      <div class="form-row mb-3">
                        <label>Watch Photos with driver's license</label>
                        <input type="file" name="watch_photos_id_drivers_license[]" id="watch_photos_id_drivers_license" multiple class="form-control mb-3  @error('watch_photos_id_drivers_license') is-invalid @enderror">
                      </div>
                      -->

                      

                    </div>

                    <div class="col-4">
                      <h6 class="card-title">Seler's Information</h6>
                      <?php 
                          $owner_user_id = isset($page['auction']->owner_user_id)?$page['auction']->owner_user_id:''; 
                          //echo $owner_user_id;
                          //var_dump($page['bidders']['1']['phone']);
                       ?>
                            
                        

                     
                      <div class="form-floating mb-3">
                        <?php // var_dump(count($page['bidders'])); ?>
                        <select  name="owner_user_id" class="owner_user_id form-select @error('owner_user_id') is-invalid @enderror" id="owner_user_id">
                          <?php if( isset($page['bidders']) ){
                                foreach ($page['bidders'] as $bid)  { ?>
                                    <option value="<?php echo $bid->acct_id;?>"  <?php echo ($owner_user_id ==  $bid->acct_id)?'selected': ''; ?>><?php echo $bid->name;?>(<?php echo $bid->email;?>)</option>
                              <?php  }
                              ?>
                              
                            <?php } ?>
                        </select>
                        <!--<input type="text" readonly name="seller_name" value="{{ old('seller_name') }}" class="form-control @error('seller_name') is-invalid @enderror" id="floatingListingSellerName" placeholder="Name">-->
                        <label for="floatingListingSellerName">Name</label>
                        @if($errors->has('owner_user_id'))
                            <div class="invalid-feedback" style="display:block;">{{ $errors->first('owner_user_id') }}</div>
                        @endif
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" readonly name="user_name" value="<?php echo isset($page['bidderD']->email)?$page['bidderD']->email: old('user_name'); ?>" class="form-control @error('user_name') is-invalid @enderror" id="floatingListingSellerUserName" placeholder="User Name">
                        <label for="floatingListingSellerUserName">User Name (not editable)</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="email" readonly name="owner_user_email" value="<?php echo isset($page['bidderD']->email)?$page['bidderD']->email: old('owner_user_email'); ?>" class="form-control @error('owner_user_email') is-invalid @enderror" id="floatingListingEmailAddress" placeholder="Email">
                        <label for="floatingListingEmailAddress">Email Address (not editable)</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" readonly name="phone_number" value="<?php echo isset($page['bidders2'][$owner_user_id]['phone'])?$page['bidders2'][$owner_user_id]['phone']: old('phone_number'); ?>" class="form-control @error('phone_number') is-invalid @enderror" id="floatingListingSellerPhone" placeholder="Phone">
                        <label for="floatingListingSellerPhone">Phone Number</label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" readonly name="country" value="<?php echo isset($page['bidders2'][$owner_user_id]['country'])?$page['bidders2'][$owner_user_id]['country']: old('country'); ?>" class="form-control @error('country') is-invalid @enderror" id="floatingListingSellerCountry" placeholder="Country">
                        <label for="floatingListingSellerCountry">Country</label>
                      </div>
                     
                      <?php 
                      $phone =  isset($page['bidders2'][$owner_user_id]['phone'])?$page['bidders2'][$owner_user_id]['phone']: old('phone_number');
                      $country=  isset($page['bidders2'][$owner_user_id]['country'])?$page['bidders2'][$owner_user_id]['country']: old('country'); 
                      //$country = $users['customers'][$j]['addresses'][0]['country'];
                      $param = '?phone='. $phone.'&country='.$country;
                      ?>
                      <div class="form-floating2 mb-5" style="padding: 0 12px;">
                        <label><?php echo isset($page['bidderD']->id)?'<a href="/bidders/edit/'.$page['bidderD']->id.'/'.$param.'">Edit Seller Info</a>':'';?></label>
                      </div>
                      
                      <?php if(isset($page['auction']->id)) { ?>
                        <div class="form-control  mb-3" style="padding: 20px 10px;">
                          <!--
                          <div class="form-check form-switch  mb-3">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Show to Public</label>
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                          </div>
                          -->
                          <div class="mb-3">
                            <label class="form-check-label" for="flexSwitchCheckDefault" style="text-align:left;font-weight:600;">Publish/Update this item to shopify?</label>
                            
                          </div>
                          <?php if(isset($page['auction']->product_id)) { ?>
                            <a href="#" id="update_publish_auction" data_id="<?php echo $page['auction']->id; ?>" class="btn btn-primary">Update Publish</a>
                            <?php } else { ?>
                            <a href="#" id="publish_auction" data_id="<?php echo $page['auction']->id; ?>" class="btn btn-primary">Publish</a>
                            <a href="#" style="display:none;" id="update_publish_auction" data_id="<?php echo $page['auction']->id; ?>" class="btn btn-primary">Update Publish</a>

                          <?php } ?>
                         
                        </div>
                       <?php } ?>

                    </div>
                    
                  
                </div>

                <div class="text-center">
                  <input type="hidden" name="submitedfrom" value="laravel">
                  <input type="hidden" name="watch_lot_id" value="<?php echo isset($page['auction']->watch_lot_id)?$page['auction']->watch_lot_id: ''; ?>">
                  <input type="hidden" name="id" value="<?php echo isset($page['auction']->id)?$page['auction']->id: ''; ?>">
                  <button type="submit" class="btn btn-primary">Save</button>
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
<!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
  <script>
     $('.watch_brand,.owner_user_id').select2();

     /* Ajax */
      // make auction goes live
      jQuery('#publish_auction').click(function(e){

          e.preventDefault();
				  //jQuery('#user-form .alert').remove();
				  
				  jQuery("#publish_auction").text('Publishing...');
				 
				  jQuery.ajaxSetup({
					  headers: {
						  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					  }
				   });
				  
				  
				   jQuery.ajax({
					  url: BASE_URL+'/push-auction-live/'+$(this).attr('data_id'),
					  method: 'get',
            data: '',
					 // data: {  id: $(this).attr('data_id') },
					  success: function(jsonData){
					       jQuery("#publish_auction").text('Published');
                 jQuery("#publish_auction").css('display','none');
                 jQuery("#update_publish_auction").css('display','block');
                 
						     console.log(jsonData);
						 if ( jsonData.result=='error' ) {
								alert(jsonData.message);
						 }
						 else {
							//jQuery('#user-form').modal('hide');
							 //datatable.ajax.reload();
							
						 }
						 
					  },
					  error: function(data){
                //button.button('reset');
                jQuery("#publish_auction").text('Error Publishing');
                var errors = data.responseJSON;
                //jQuery.each(errors['errors'],function(e,v){
                 // jQuery('#user-form .modal-footer').prepend('<div class="alert alert-danger">'+v+'</div>');
                //});
					  }});
				});
       

        jQuery('#update_publish_auction').click(function(e){

          e.preventDefault();
          //jQuery('#user-form .alert').remove();

          jQuery("#update_publish_auction").text('Publish updating...');

          jQuery.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
          });


          jQuery.ajax({
            url: BASE_URL+'/update-auction-live/'+$(this).attr('data_id'),
            method: 'GET',
            data: '',
          // data: {  id: $(this).attr('data_id') },
            success: function(jsonData){
                jQuery("#update_publish_auction").text('Published Updated');
                console.log(jsonData);
            if ( jsonData.result=='error' ) {
                alert(jsonData.message);
            }
            else {
              //jQuery('#user-form').modal('hide');
              //datatable.ajax.reload();
              
            }
            
            },
            error: function(data){
                //button.button('reset');
                jQuery("#update_publish_auction").text('Error Publish Updating');
                var errors = data.responseJSON;
                //jQuery.each(errors['errors'],function(e,v){
                // jQuery('#user-form .modal-footer').prepend('<div class="alert alert-danger">'+v+'</div>');
                //});
            }});
          });


  </script>
 @endsection