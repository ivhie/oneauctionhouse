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
        <li class="breadcrumb-item active">Unfulfill Auctions</li>
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
   
    <style>
      .disable_stat { 
        background-color:#696565;
        color:#fff;
      }
      .active_stat {
        background-color:#0b5e0e;
        color:#fff;
      }
     


      .accordion-button:not(.collapsed) {
          color: #fff;
          background-color: #0b5e0e;
      }
    </style>
      <?php
        //var_dump($page['bidderD']->name);
        $seller_user_id = isset($page['auction']->owner_user_id)?$page['auction']->owner_user_id:'';
        $seller_name = isset($page['bidderD']->name)?$page['bidderD']->name: '';
        //var_dump($seller_name);
        $seller_email = isset($page['bidderD']->email)?$page['bidderD']->email: '';
        $seller_phone =  isset($page['bidders2'][$seller_user_id]['phone'])?$page['bidders2'][$seller_user_id]['phone']: '';
        $seller_country=  isset($page['bidders2'][$seller_user_id]['country'])?$page['bidders2'][$seller_user_id]['country']: ''; 
      
        $buyer_user_id = isset($page['buyer']->acct_id)?$page['buyer']->acct_id:'';
        $buyer_name = isset($page['buyer']->name)?$page['buyer']->name: '';
        $buyer_email = isset($page['buyer']->email)?$page['buyer']->email: '';
        $buyer_phone =  isset($page['bidders2'][$buyer_user_id]['phone'])?$page['bidders2'][$buyer_user_id]['phone']: '';
        $buyer_country=  isset($page['bidders2'][$buyer_user_id]['country'])?$page['bidders2'][$buyer_user_id]['country']: ''; 
      
      
      ?>
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?php echo $page['subtitle']; ?></h5>
             <?php
             //date_format($page['auction']->updated_at, "d F Y, h:i:sa" )
             //var_dump($page['fulfillment']);
             ?>
              <!-- Floating Labels Form -->
              <form class="row g-3"  style="justify-content: center;" action="{{url('/unfulfilled-auctions/store')}}" id="fulfillmentform" method="post" enctype="multipart/form-data">
              @csrf
                <h3 style="margin-bottom:0px;">LOT NUMBER : #<?php echo isset($page['auction']->watch_lot_id)?$page['auction']->watch_lot_id: ''; ?> </h3>
                <span style="margin-top:0px;">Last saved: <?php echo isset($page['auction']->updated_at)?date_format($page['auction']->updated_at, "d F Y, h:i a" ): ''; ?></span>
                <div class="row">
                    <div class="col-md-4">
                      
                      <h6 class="card-title">Details</h6>
                      <ul style="list-style:none;padding:0;" class="mb-3">
                        <li><strong>Auction Item :</strong> <?php echo isset($page['auction']->bidding_title)?$page['auction']->bidding_title: old('bidding_title'); ?></li>
                      </ul>
                      <ul style="list-style:none;padding:0;" class="mb-3">
                        <li><strong>Seller Info:</strong></li>
                        <li><span>Name :</span> <?php echo  $seller_name; ?></li>
                        <li><span>Phone :</span> <?php echo  $seller_phone; ?></li>
                        <li><span>Email:</span> <?php echo  $seller_email; ?></li>
                        <li><span>Location :</span> <?php echo  $seller_country; ?></li>
                        
                      </ul>
                      <ul style="list-style:none;padding:0;" class="mb-3">
                        <li><strong>Buyer Info:</strong></li>
                        <li><span>Name :</span> <?php echo  $buyer_name; ?></li>
                        <li><span>Phone :</span> <?php echo  $buyer_phone; ?></li>
                        <li><span>Email:</span> <?php echo  $buyer_email; ?></li>
                        <li><span>Location :</span> <?php echo  $buyer_country; ?></li>
                      </ul>

                    
                    </div>


                    <div class="col-md-8">
                    <!-- Default Accordion -->
                    <?php 
                    $disbtn1 = '';
                    $disbtn2 = 'disabled';
                    $disbtn3 = 'disabled';
                    $disbtn4 = 'disabled';
                    $disbtn5 = 'disabled';
                    $disbtn6 = 'disabled';
                    $disbtn7 = 'disabled';

                    $btn_class1 = 'active_stat';
                    $btn_class2 = '';
                    $btn_class3 = '';
                    $btn_class4 = '';
                    $btn_class5 = '';
                    $btn_class6 = '';
                    $btn_class7 = '';
                  
                    
                    $collapse1 = 'show';
                    $collapse2 = '';
                    $collapse3 = '';
                    $collapse4 = '';
                    $collapse5 = '';
                    $collapse6 = '';
                    $collapse7 = '';
                      $steps =  isset($page['fulfillment']->steps)?$page['fulfillment']->steps:''; 

                      if($steps == '' ){
                        $disbtn1 = '';
                        $disbtn2 = 'disabled';
                        $disbtn3 = 'disabled';
                        $disbtn4 = 'disabled';
                        $disbtn5 = 'disabled';
                        $disbtn6 = 'disabled';
                        $disbtn7 = 'disabled';
                        
                        $collapse1 = 'show';
                        $collapse2 = '';
                        $collapse3 = '';
                        $collapse4 = '';
                        $collapse5 = '';
                        $collapse6 = '';
                        $collapse7 = '';

                        
                        $btn_class1 = 'active_stat';
                        $btn_class2 = 'disable_stat';
                        $btn_class3 = 'disable_stat';
                        $btn_class4 = 'disable_stat';
                        $btn_class5 = 'disable_stat';
                        $btn_class6 = 'disable_stat';
                        $btn_class7 = 'disable_stat';
                        
                    }


                      if($steps == 1){
                          $disbtn1 = '';
                          $disbtn2 = '';
                          $disbtn3 = 'disabled';
                          $disbtn4 = 'disabled';
                          $disbtn5 = 'disabled';
                          $disbtn6 = 'disabled';
                          $disbtn7 = 'disabled';
                          
                          $collapse1 = '';
                          $collapse2 = 'show';
                          $collapse3 = '';
                          $collapse4 = '';
                          $collapse5 = '';
                          $collapse6 = '';
                          $collapse7 = '';

                          
                          $btn_class1 = 'active_stat';
                          $btn_class2 = 'active_stat';
                          $btn_class3 = 'disable_stat';
                          $btn_class4 = 'disable_stat';
                          $btn_class5 = 'disable_stat';
                          $btn_class6 = 'disable_stat';
                          $btn_class7 = 'disable_stat';
                          
                      }

                      if($steps == 2){
                        $disbtn1 = '';
                        $disbtn2 = '';
                        $disbtn3 = '';
                        $disbtn4 = 'disabled';
                        $disbtn5 = 'disabled';
                        $disbtn6 = 'disabled';
                        $disbtn7 = 'disabled';
                        
                        $collapse1 = '';
                        $collapse2 = '';
                        $collapse3 = 'show';
                        $collapse4 = '';
                        $collapse5 = '';
                        $collapse6 = '';
                        $collapse7 = '';

                        $btn_class1 = 'active_stat';
                        $btn_class2 = 'active_stat';
                        $btn_class3 = 'active_stat';
                        $btn_class4 = 'disable_stat';
                        $btn_class5 = 'disable_stat';
                        $btn_class6 = 'disable_stat';
                        $btn_class7 = 'disable_stat';

                     }

                     if($steps == 3){
                      $disbtn1 = '';
                      $disbtn2 = '';
                      $disbtn3 = '';
                      $disbtn4 = '';
                      $disbtn5 = 'disabled';
                      $disbtn6 = 'disabled';
                      $disbtn7 = 'disabled';
                      
                      $collapse1 = '';
                      $collapse2 = '';
                      $collapse3 = '';
                      $collapse4 = 'show';
                      $collapse5 = '';
                      $collapse6 = '';
                      $collapse7 = '';

                      $btn_class1 = 'active_stat';
                      $btn_class2 = 'active_stat';
                      $btn_class3 = 'active_stat';
                      $btn_class4 = 'active_stat';
                      $btn_class5 = 'disable_stat';
                      $btn_class6 = 'disable_stat';
                      $btn_class7 = 'disable_stat';
                   }

                   if($steps == 4){
                    $disbtn1 = '';
                    $disbtn2 = '';
                    $disbtn3 = '';
                    $disbtn4 = '';
                    $disbtn5 = '';
                    $disbtn6 = 'disabled';
                    $disbtn7 = 'disabled';
                    
                    $collapse1 = '';
                    $collapse2 = '';
                    $collapse3 = '';
                    $collapse4 = '';
                    $collapse5 = 'show';
                    $collapse6 = '';
                    $collapse7 = '';

                    $btn_class1 = 'active_stat';
                    $btn_class2 = 'active_stat';
                    $btn_class3 = 'active_stat';
                    $btn_class4 = 'active_stat';
                    $btn_class5 = 'active_stat';
                    $btn_class6 = 'disable_stat';
                    $btn_class7 = 'disable_stat';


                    }
                    if($steps == 5){
                      $disbtn1 = '';
                      $disbtn2 = '';
                      $disbtn3 = '';
                      $disbtn4 = '';
                      $disbtn5 = '';
                      $disbtn6 = '';
                      $disbtn7 = 'disabled';
                      
                      $collapse1 = '';
                      $collapse2 = '';
                      $collapse3 = '';
                      $collapse4 = '';
                      $collapse5 = '';
                      $collapse6 = 'show';
                      $collapse7 = '';

                      $btn_class1 = 'active_stat';
                      $btn_class2 = 'active_stat';
                      $btn_class3 = 'active_stat';
                      $btn_class4 = 'active_stat';
                      $btn_class5 = 'active_stat';
                      $btn_class6 = 'active_stat';
                      $btn_class7 = 'disable_stat';
                  }

                  if($steps == 6){
                    $disbtn1 = '';
                    $disbtn2 = '';
                    $disbtn3 = '';
                    $disbtn4 = '';
                    $disbtn5 = '';
                    $disbtn6 = '';
                    $disbtn7 = '';
                    
                    $collapse1 = '';
                    $collapse2 = '';
                    $collapse3 = '';
                    $collapse4 = '';
                    $collapse5 = '';
                    $collapse6 = '';
                    $collapse7 = 'show';

                    $btn_class1 = 'active_stat';
                    $btn_class2 = 'active_stat';
                    $btn_class3 = 'active_stat';
                    $btn_class4 = 'active_stat';
                    $btn_class5 = 'active_stat';
                    $btn_class6 = 'active_stat';
                    $btn_class7 = 'active_stat';
                }
                if($steps == 7){
                  $disbtn1 = '';
                  $disbtn2 = '';
                  $disbtn3 = '';
                  $disbtn4 = '';
                  $disbtn5 = '';
                  $disbtn6 = '';
                  $disbtn7 = '';
                  
                  $collapse1 = '';
                  $collapse2 = '';
                  $collapse3 = '';
                  $collapse4 = '';
                  $collapse5 = '';
                  $collapse6 = '';
                  $collapse7 = '';
                  $btn_class1 = 'active_stat';
                  $btn_class2 = 'active_stat';
                  $btn_class3 = 'active_stat';
                  $btn_class4 = 'active_stat';
                  $btn_class5 = 'active_stat';
                  $btn_class6 = 'active_stat';
                  $btn_class7 = 'active_stat';
              }
                      
                    
                    ?>
                    <div class="accordion" id="accordionExample">
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button class="accordion-button collapsed <?php echo $btn_class1;?>" <?php echo $disbtn1;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           Step 1 : Pending Authentication
                          </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse <?php echo $collapse1;?>" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                              <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The seller needs to send the item to the authentication center for checking and approve the winning bid.
                               The admin keeps track of this stage to make sure the seller acts within a set time.</p>
                               <div class="row mb-3">
                                  <div class="col-4"><strong>Item Shipping:</strong></div>
                                  <div class="col-8">
                                    <div class="form-check">
                                      <input class="form-check-input" name="step1_shipping" <?php echo isset($page['fulfillment']->step1_shipping)?'checked':'';?> type="checkbox" value="Yes" id="gridCheck1" readonly disabled>
                                      <label class="form-check-label" for="gridCheck1">
                                        Sent
                                      </label>
                                    </div>
                                  </div>
                              </div>


                          </div>
                        </div>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                          <button  class="accordion-button collapsed <?php echo $btn_class2;?>"  <?php echo $disbtn2;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                           Step 2 : Item in Transit to Authentication Center
                          </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse <?php echo $collapse2;?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                              <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The seller confirms they have shipped the item to the authentication center and gives the FedEx tracking number. The admin checks the tracking and updates the status.</p>
                               <!--
                               <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Shipping Date:</strong></label>
                                <div class="col-sm-8">
                                  <input readonly type="text"  value="<?php echo isset($page['fulfillment']->step2_shipping_date)?$page['fulfillment']->step2_shipping_date:'';?>" name="step2_shipping_date" id="step2_shipping_date222" class="form-control" placeholder="Date">
                                </div>
                              </div>-->
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Shipping Method:</strong></label>
                                <div class="col-sm-8">
                                  <input readonly type="text"  value="<?php echo isset($page['fulfillment']->step2_fedx_tracking_status)?$page['fulfillment']->step2_fedx_tracking_status:'';?>" name="step2_fedx_tracking_status" class="form-control" placeholder="Shipping Method">
                                </div>
                              </div>

                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Tracking number:</strong></label>
                                <div class="col-sm-8">
                                  <input readonly type="text"  value="<?php echo isset($page['fulfillment']->step2_fedx_tracking_number)?$page['fulfillment']->step2_fedx_tracking_number:'';?>" name="step2_fedx_tracking_number" class="form-control" placeholder="Tracking Number">
                                </div>
                              </div>
                             

                             

                          </div>
                        </div>
                      </div>

                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                          <button class="accordion-button collapsed <?php echo $btn_class3;?>"  <?php echo $disbtn3;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                          Step 3 : Item Received by Authentication Center
                          </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse <?php echo $collapse3;?>" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                          <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The authentication center confirms they have received the item. The admin updates the status and informs the seller and buyer. </p>
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Arrived Date:</strong></label>
                                <div class="col-sm-8">
                                  <input type="text"  value="<?php echo isset($page['fulfillment']->step3_arrive_date)?$page['fulfillment']->step3_arrive_date:'';?>" name="step3_arrive_date" id="step3_arrive_date" class="form-control" placeholder="Date">
                                </div>
                              </div>
                              <div class="row mb-3">
                                  <div class="col-4"><strong>Item Arrived:</strong></div>
                                  <div class="col-8">
                                    <div class="form-check">
                                      <input class="form-check-input" <?php echo isset($page['fulfillment']->step3_item_arrived)?'checked':'';?> name="step3_item_arrived" type="checkbox"  id="gridCheck1">
                                      <label class="form-check-label" for="gridCheck1">
                                        Yes
                                      </label>
                                    </div>
                                  </div>
                              </div>
                        
                         </div>
                        </div>
                      </div>


                      <div class="accordion-item">
                        <h2 class="accordion-header" id="heading4">
                          <button class="accordion-button collapsed <?php echo $btn_class4;?>"  <?php echo $disbtn4;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                          Step 4 : Item Authenticated  
                          </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse <?php echo $collapse4;?>" aria-labelledby="heading4" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The authentication center checks if the item is genuine and sends a receipt to the buyer and seller by email. The admin makes sure this is done and the receipt is sent.</p>
                            <?php $step4_item_genuine = isset($page['fulfillment']->step4_item_genuine)?$page['fulfillment']->step4_item_genuine:''; ?>
                            <!--
                            <fieldset class="row mb-3">
                              <legend class="col-form-label col-sm-4 pt-0"><strong>Genuine:</strong></legend>
                              <div class="col-sm-8">
                                <div class="form-check">
                                  <input class="form-check-input"  <?php echo ($step4_item_genuine == 'Yes')?'checked':'';?>  name="step4_item_genuine" type="radio"  id="gridRadios1" value="Yes" />
                                  <label class="form-check-label" for="gridRadios1">
                                    Yes
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" <?php echo ($step4_item_genuine == 'No')?'checked':'';?>  type="radio" name="step4_item_genuine" id="gridRadios2" value="No" />
                                  <label class="form-check-label" for="gridRadios2">
                                    No
                                  </label>
                                </div>
                              </div>
                            </fieldset>-->
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Test Date:</strong></label>
                                <div class="col-sm-8">
                                  <input type="text"  id="step4_test_date" value="<?php echo isset($page['fulfillment']->step4_test_date)?$page['fulfillment']->step4_test_date:'';?>" name="step4_test_date" class="form-control" placeholder="Date" />
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Upload Authenticated File:</strong></label>
                                <div class="col-sm-8">
                                  <input type="file"  id="step4_upload_authenticated_file" value="<?php echo isset($page['fulfillment']->step4_upload_authenticated_file)?$page['fulfillment']->step4_upload_authenticated_file:'';?>" name="step4_upload_authenticated_file" class="form-control" placeholder="Upload File" />
                                  <?php
                                       if(isset($page['fulfillment']->step4_upload_authenticated_file)){
                                          //echo '<a href="'.url('/unfulfilled-auctions/download/'.$page['auction']->watch_lot_id.'/'.$page['fulfillment']->step4_upload_authenticated_file).'" style="margin-top:8px;" class="btn btn-warning">Download PDF</a>';
                                          echo '<a href="'.url('unfulfilled-auctions/download/'.$page['auction']->watch_lot_id).'/'.$page['fulfillment']->step4_upload_authenticated_file.'" style="margin-top:8px;margin-right:10px;" class="btn btn-warning">Download PDF</a>';
                                          echo '<a target="_blank" href="'.url('public/authenticated/lot-'.$page['auction']->watch_lot_id.'/'.$page['fulfillment']->step4_upload_authenticated_file).'" style="margin-top:8px;" class="btn btn-warning">View PDF</a>';
                                          
                                         
                                       }
                                  ?>
                                </div>
                              </div>

                          </div>
                        </div>
                      </div>

                      <div class="accordion-item">
                        <h2 class="accordion-header" id="heading5">
                          <button class="accordion-button collapsed <?php echo $btn_class5;?>"  <?php echo $disbtn5;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                             Step 5 : Pending Buyer's Payment  
                          </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse <?php echo $collapse5;?>" aria-labelledby="heading5" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                              <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The buyer needs to wire the payment to the seller. The admin keeps an eye on this stage and reminds the buyer if needed to ensure a smooth transaction.</p>
                              <div class="row mb-3">
                                <label class="col-sm-4 col-form-label"><strong>Payment Status:</strong></label>
                                
                                <?php $step5_buyer_payment =  isset($page['fulfillment']->step5_buyer_payment)?'Paid':'';?>
                                <div class="col-sm-8">
                                  <select class="form-select"  name="step5_buyer_payment" aria-label="Default select example" disabled>
                                    <option value="">Status</option>
                                    <option value="Pending"  <?php echo ($step5_buyer_payment=='Pending')?'selected':'';?>>Pending</option>
                                    <option value="Paid"  <?php echo ($step5_buyer_payment=='Paid')?'selected':'';?>>Paid</option>
                                  </select>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>

                      <div class="accordion-item">
                        <h2 class="accordion-header" id="heading6">
                          <button class="accordion-button collapsed <?php echo  $btn_class6;?>" <?php echo $disbtn6;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                            Step 6 : Payment Received by Seller  
                          </button>
                        </h2>
                        <div id="collapse6" class="accordion-collapse collapse <?php echo $collapse6;?>" aria-labelledby="heading6" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                           <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The seller confirms they have received the payment. The admin checks the payment confirmation and updates the status, getting ready for the last stage.</p>
                             
                              <div class="row mb-3">
                                <label class="col-sm-4 col-form-label"><strong>Payment Received:</strong></label>
                                <div class="col-sm-8">
                                  <?php // $step6_payment_receiveds =  isset($page['fulfillment']->step6_payment_received)?$page['fulfillment']->step6_payment_received:'';?>
                                  <?php $step5_buyer_payment =  isset($page['fulfillment']->step5_buyer_payment)?'Yes':'';?>
                                  <select class="form-select" name="step6_payment_received" aria-label="Default select example" disabled>
                                    <option selected="">Status</option>
                                    <option value="Yes" <?php echo ($step5_buyer_payment=='Yes')?'selected':'';?>>Yes</option>
                                    <option value="No" <?php echo ($step5_buyer_payment=='No')?'selected':'';?>>No</option>
                                  </select>

                                  <?php
                                       if(isset($page['fulfillment']->step5_buyer_payment)){

                                          echo '<a href="'.url('unfulfilled-auctions/downloadPaymentFile/'.$page['auction']->watch_lot_id).'/'.$page['fulfillment']->step5_buyer_payment.'" style="margin-top:8px;margin-right:10px;" class="btn btn-warning">Download File</a>';
                                          echo '<a target="_blank" href="'.url('public/paymentfile/lot-'.$page['auction']->watch_lot_id.'/'.$page['fulfillment']->step5_buyer_payment).'" style="margin-top:8px;" class="btn btn-warning">View File</a>';
                                          
                                       }
                                  ?>


                                </div>
                              </div>
                              <!--
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Payment Date:</strong></label>
                                <div class="col-sm-8">
                                  <input type="text" id="step6_payment_date"  value="<?php //echo isset($page['fulfillment']->step6_payment_date)?$page['fulfillment']->step6_payment_date:'';?>" name="step6_payment_date" class="form-control" placeholder="Date">
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Payment Reference Number:</strong></label>
                                <div class="col-sm-8">
                                  <input type="text" value="<?php //echo isset($page['fulfillment']->step6_payment_ref)?$page['fulfillment']->step6_payment_ref:'';?>" name="step6_payment_ref" class="form-control" placeholder="Ref Number">
                                </div>
                              </div>
                              -->
                             
                          
                          </div>
                        </div>
                      </div>

                      <div class="accordion-item">
                        <h2 class="accordion-header" id="heading7">
                          <button class="accordion-button collapsed <?php echo $btn_class7;?>" <?php echo $disbtn7;?> type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                          Step 7 : Item Shipped to Buyer  
                          </button>
                        </h2>
                        <div id="collapse7" class="accordion-collapse collapse <?php echo $collapse7;?>" aria-labelledby="heading7" data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <p style="font-style:italic;color:#504e4ef7;"><strong>Description:</strong> The authentication center ships the item to the buyer's address. The admin works with the authentication center to make sure the item is shipped quickly and gives the buyer the tracking information.</p>
                             
                             <div class="row mb-3">
                                <label class="col-sm-4 col-form-label"><strong>Item Ship out:</strong></label>
                                <div class="col-sm-8">
                                <?php $step7_ship_out =  isset($page['fulfillment']->step7_ship_out)?$page['fulfillment']->step7_ship_out:'';?>
                                  <select class="form-select"  name="step7_ship_out" aria-label="Default select example">
                                    <option value="">Status</option>
                                    <option value="Yes" <?php echo ($step7_ship_out=='Yes')?'selected':'';?> >Yes</option>
                                    <option value="No" <?php echo ($step7_ship_out=='No')?'selected':'';?>>No</option>
                                  </select>
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Payment Date:</strong></label>
                                <div class="col-sm-8">
                                  <input type="text"  id="step7_ship_out_date" value="<?php echo isset($page['fulfillment']->step7_ship_out_date)?$page['fulfillment']->step7_ship_out_date:'';?>" name="step7_ship_out_date" class="form-control" placeholder="Date">
                                </div>
                              </div>
                              <div class="row mb-3">
                                <label for="inputText" class="col-sm-4 col-form-label"><strong>Tracking Information:</strong></label>
                                <div class="col-sm-8">
                                  <input type="text" value="<?php echo isset($page['fulfillment']->step7_ship_out_tracking_number)?$page['fulfillment']->step7_ship_out_tracking_number:'';?>" name="step7_ship_out_tracking_number" class="form-control" placeholder="Tracking Number">
                                </div>
                              </div>
                          
                          </div>
                        </div>
                      </div>
                      <div class="row">
                          <input type="hidden" name="lot_id" value="<?php echo $page['auction']->watch_lot_id; ?>" />
                          <input type="hidden" name="auction_id" value="<?php echo $page['auction']->id; ?>" />
                          <input type="hidden" name="id" value="<?php echo isset($page['fulfillment']->id)?$page['fulfillment']->id:'';?>" />
                          <input type="hidden" name="steps" value="<?php echo isset($page['fulfillment']->steps)?$page['fulfillment']->steps:'';?>" />
                          <input type="hidden" name="buyer_id" value="<?php echo $buyer_user_id; ?>" />
                          <input type="hidden" name="acct_id" value="<?php echo $seller_user_id; ?>" />
                          
                        
                          <div class="col-md-2 mt-3">
                            <button type="submit" id="savefulfillment" class="btn btn-primary pull-left">Save</button>
                          </div>
                        
                          <!--
                          <div class="col-6 mt-3">
                            <button type="button" id="rejectfulfillment" class="btn btn-danger pull-right ml-3">Reject</button>
                          </div>
                            -->
                          <div class="col-md-4 mt-3">
                            <?php echo ($page['product_url']);?>
                          </div>
                          
                      </div>

                    </div><!-- End Default Accordion Example -->
                    </div>

                  
                </div>

                
              </form><!-- End floating Labels Form -->
            </div>
          </div>
        </div>
      </div>

    </section>

    <script>
        $(document).ready(function () {
 
            $(function () {
                $("#step2_shipping_date").
                datepicker();
                $("#step3_arrive_date").
                datepicker();
                $("#step4_test_date").
                datepicker();
                $("#step6_payment_date").
                datepicker();
                $("#step7_ship_out_date").
                datepicker();
                
            });
        }) 
    </script>

    
@endsection

@section('page_script')
<!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
  <script>

    
     // $( "#step2_shipping_date" ).datepicker();
    
     /* Ajax */
     /*
      // make auction goes live
      jQuery('#savefulfillment').click(function(e){

          e.preventDefault();
				  //jQuery('#user-form .alert').remove();
				  
				  jQuery("#savefulfillment").text('Saving...');
				 
				  jQuery.ajaxSetup({
					  headers: {
						  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					  }
				   });
				  
				  
				   jQuery.ajax({
					  url: BASE_URL+'/unfulfilled-auctions/savefullfillment',
					  type: 'POST',
					  data:  $('#fulfillmentform').serialize(),
					  success: function(jsonData){
					       jQuery("#savefulfillment").text('Saved');
                 //jQuery("#savefulfillment").css('display','none');
                 //jQuery("#update_publish_auction").css('display','block');
                 
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
                jQuery("#savefulfillment").text('Error Saving');
                var errors = data.responseJSON;
                //jQuery.each(errors['errors'],function(e,v){
                 // jQuery('#user-form .modal-footer').prepend('<div class="alert alert-danger">'+v+'</div>');
                //});
					  }});
				});
        */
       

        jQuery('#rejectfulfillment').click(function(e){

          e.preventDefault();
          //jQuery('#user-form .alert').remove();

          jQuery("#rejectfulfillment").text('Publish updating...');

          jQuery.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
          });


          jQuery.ajax({
            url: BASE_URL+'/unfulfilled-update-auction-live/'+$(this).attr('data_id'),
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