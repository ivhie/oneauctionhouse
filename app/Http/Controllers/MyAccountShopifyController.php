<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Comments;

class MyAccountShopifyController extends Controller
{
    
       

         public function updateConfirmpayment($product_id=null, $acct_id=null, $auction_id=null) {
            //$status = 'success';
            
           //$step6_payment_received = isset(request()->step6_payment_received)?request()->step6_payment_received:'';

           // $product_id = isset(request()->product_id)?request()->product_id:'';
           // $auction_id = isset(request()->auction_id)?request()->auction_id:'';
           // $acct_id = isset(request()->acct_id)?request()->acct_id:'';

         // $fulfillment->steps = 2;
            if(  DB::table('fulfillment')
            ->where('product_id','=',$product_id)
            ->where('auction_id','=', $auction_id)
            ->where('acct_id', '=',$acct_id)
            ->where('fulfillment_status','=', 'active')
            ->where('status','=', 'active')
            ->update([
               'step6_payment_received' => 'Yes',
               'steps' => '6'
            
            ])){

               $status = 'success';
            } else {
            // $status = 'failed';
               $status = 'failed';
            }
         
         $data = [
            "status"=>$status,
            //"response"=>$response,
            ];

         return response()->json($data);
            
      }

        public function updateShippingTrackingID(Request $request) {

               $step2_fedx_tracking_number = isset(request()->tracking_id)?request()->tracking_id:'';
               $step2_fedx_tracking_status = isset(request()->shipping_method)?request()->shipping_method:'';

               $product_id = isset(request()->product_id)?request()->product_id:'';
               $auction_id = isset(request()->auction_id)?request()->auction_id:'';
               $acct_id = isset(request()->acct_id)?request()->acct_id:'';

              // $fulfillment->steps = 2;
               if(  DB::table('fulfillment')
               ->where('product_id','=',$product_id)
               ->where('auction_id','=', $auction_id)
               ->where('acct_id', '=',$acct_id)
               ->where('fulfillment_status','=', 'active')
               ->where('status','=', 'active')
               ->update([
                   'step2_fedx_tracking_number' => $step2_fedx_tracking_number,
                   'step2_fedx_tracking_status' => $step2_fedx_tracking_status,
                   'step1_shipping' => 'Yes',
                   'steps' => '2'
               
               ])){

                  $status = 'success';
               } else {
                 // $status = 'failed';
                  $status = 'failed';
               }

              $data = [
                "status"=>$status,
                //"response"=>$response,
                ];
    
            return response()->json($data);
                
        }


        public function updateShippingPaymentFile(Request $request) {

         //$step2_fedx_tracking_number = isset(request()->step5_buyer_payment)?request()->step5_buyer_payment:'';
         //$step2_fedx_tracking_status = isset(request()->shipping_method)?request()->shipping_method:'';

         $product_id = isset(request()->product_id)?request()->product_id:'';
         $auction_id = isset(request()->auction_id)?request()->auction_id:'';
         $acct_id = isset(request()->acct_id)?request()->acct_id:'';

         $payment_file = '';
         
         if($request->hasFile('step5_buyer_payment')) {
           
            $file = $request->file('step5_buyer_payment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('paymentfile/lot-'.$auction_id);
            $file->move($destinationPath, $filename);
            $payment_file = $filename;
            
        }


        // $fulfillment->steps = 2;
         if(  DB::table('fulfillment')
         ->where('product_id','=',$product_id)
         ->where('auction_id','=', $auction_id)
         ->where('acct_id', '=',$acct_id)
         ->where('fulfillment_status','=', 'active')
         ->where('status','=', 'active')
         ->update([
             'step5_buyer_payment' => $payment_file,
             'step6_payment_date' => date("F j, Y"),
             'steps' => '5',
         
         ])){

            $status = 'success';
         } else {
           // $status = 'failed';
            $status = 'failed-'.$product_id.'-'.$auction_id.'-'.$acct_id;
         }

        $data = [
          "status"=>$status,
          //"response"=>$response,
          ];

      return response()->json($data);
          
      }


        public function getShippingTrackingID($product_id=null, $acct_id=null, $auction_id=null) {


           // $fulfillment->steps = 2;
           $fulfillment = DB::table('fulfillment')
            ->where('product_id','=',$product_id)
            ->where('auction_id','=', $auction_id)
            ->where('acct_id', '=',$acct_id)
           // ->where('fulfillment_status','=', 'active')
            ->where('status','=', 'active')
            ->first(
                ['step2_fedx_tracking_number',
                'step2_fedx_tracking_status']
            );
            
            
            if( $fulfillment) {
               $status = 'success';
               $fulfillment_data = $fulfillment;
            } else {
               $status = 'empty';
               $fulfillment_data = '';
            }

           $data = [
             "status"=>$status,
             "fulfillment"=>$fulfillment_data,
          
            ];
 
         return response()->json($data);
             
       } 


       public function getPaymentFile($product_id=null, $acct_id=null, $auction_id=null) {


         // $fulfillment->steps = 2;
         $fulfillment = DB::table('fulfillment')
          ->where('product_id','=',$product_id)
          ->where('auction_id','=', $auction_id)
          ->where('acct_id', '=',$acct_id)
         // ->where('fulfillment_status','=', 'active')
          ->where('status','=', 'active')
          ->first(
              ['step5_buyer_payment']
          );
          
          
          if( $fulfillment) {
             $status = 'success';
             $fulfillment_data = $fulfillment;
          } else {
             $status = 'empty';
             $fulfillment_data = '';
          }

         $data = [
           "status"=>$status,
           "fulfillment"=>$fulfillment_data,
        
          ];

       return response()->json($data);
           
     } 



    


}
