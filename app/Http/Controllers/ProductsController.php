<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuctionItems;
use App\Models\Bidders;
use App\Models\Bidding;
use App\Models\Comments;
use App\Models\Fulfillment;

use App\Services\ShopifyService;

use App\Http\Controllers\EmailTemplateController;

class ProductsController extends Controller
{
    
    protected $shopifyService;
    public $sendingEmail;
    public function __construct(EmailTemplateController $emaitemplate, ShopifyService $shopifyService )
    {
        $this->shopifyService = $shopifyService;
        $this->sendingEmail = $emaitemplate;
    }
      
    
    public function getNegotiationProductsBySeller($acct_id=null,$product_status=null){
       
        $bidding_item = array();
        $negotiations = array();
        // get item

    
        // if live, ended or completed
         $latestBid = DB::table('bidding')
        ->select('lot_id', DB::raw('MAX(bid_amt) as bid_amt'))
        ->where('status','!=','deleted')
        ->groupBy('lot_id');

           
        $items = DB::table('auction_items')
        // ->leftJoinSub($latestBid, 'bidding', function ($join) {
        //    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
        //})
        ->leftJoin('fulfillment', 'fulfillment.product_id', '=', 'auction_items.product_id')
        //->leftJoin('bidding', 'bidding.lot_id', '=', 'auction_items.watch_lot_id')
        ->where('auction_items.owner_user_id','=',$acct_id)
        ->where('auction_items.product_status','=','negotiations')
        ->where('auction_items.status','!=','deleted')
        ->select(
        
            'auction_items.product_url as product_url',
            'auction_items.watch_lot_id',
            'auction_items.id',
            'auction_items.id as auction_id',
            'auction_items.product_id',
            'auction_items.bidding_title',
            'auction_items.watch_brand',
            'auction_items.reserves',
            'auction_items.bidding_date',
            'auction_items.bidding_time',
            'auction_items.watch_photos',
            'auction_items.owner_user_id',
            'auction_items.product_status',
            'fulfillment.step2_fedx_tracking_number',
            'fulfillment.step2_fedx_tracking_status',
            'fulfillment.steps',
            'fulfillment.id as fulfillment_id',
            'fulfillment.step4_upload_authenticated_file',
            'fulfillment.step5_buyer_payment',
            'fulfillment.step6_payment_date',
            
            
        )
        ->get();

            
        // get hightest bidding data
        if($items){ // if bidding item has entry
            
            foreach( $items as $item ) {
                    $bidding_item[$item->watch_lot_id] = DB::table('bidding')
                    ->where('lot_id', $item->watch_lot_id)
                    ->select(
                        'bid_amt',
                        'acct_id',
                        'id',
                    )
                    ->latest()
                    ->first();
            }
            
        }


        // get negotionation data
        if($items){ // if negotionation item has entry
            
            foreach( $items as $item ) {
                    $negotiations[$item->auction_id] = DB::table('negotiations')
                    ->where('auction_id', $item->auction_id)
                    ->select('*')
                    ->latest()
                    ->first();
            }
            
        }

               

        
        //var_dump($items);
        $items2 = AuctionItems::select('*');
        $items2 = $items2->where('owner_user_id','=',$acct_id);
        $items2 = $items2->where('status','!=','deleted');
        if($product_status){
            $items2 = $items2->where('product_status','=',$product_status);
        }
       
        $items2 = $items2->count();

    

        if(isset($items)){
           $status= 'success';
           //$items =  implode(",", $items);
        } else {
            $status= 'failed';
           
        }

        //query likes // pass this like count
        $likes = array();
        if(isset($items)){
        foreach($items as $item) {

            $likes[$item->auction_id] = DB::table('favorites')
            ->where('auction_id','=', $item->auction_id)
            ->where('like','=','1')
            ->get()
            ->count();

            }
        }


        //echo json_encode(["status"=>'success','msg'=>'TEST','items'=>$items]);
        $data = [
            "status"=>$status,
            'msg'=>$status,
            "items"=>$items,
            "count"=>$items2,
            "likes"=>$likes,
            "bidding_items"=>$bidding_item,
            "negotiations"=>$negotiations,
            //"fulfillment"=>$fulfillment_data,
        ];
        return response()->json($data);

  
    }


    //
    public function getProducts($acct_id=null,$product_status=null){
       
        $bidding_item = array();
        $negotiations = array();
        // get item

        if($product_status =='pending' || $product_status =='rejected' ){

        
            $items = AuctionItems::select('*');
            $items = $items->where('owner_user_id','=',$acct_id);
            $items = $items->where('status','!=','deleted');
            if($product_status){
                $items = $items->where('product_status','=',$product_status);
            }
            $items = $items->get();

       } else {
        
         // if live, ended or completed
         $latestBid = DB::table('bidding')
        ->select('lot_id', DB::raw('MAX(bid_amt) as bid_amt'))
        ->where('status','!=','deleted')
        ->groupBy('lot_id');
           
            if($product_status =='live'){


                $items = DB::table('auction_items')
               // ->leftJoinSub($latestBid, 'bidding', function ($join) {
                //    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
                //})
                ->leftJoin('fulfillment', 'fulfillment.product_id', '=', 'auction_items.product_id')
                //->leftJoin('bidding', 'bidding.lot_id', '=', 'auction_items.watch_lot_id')
                ->where('auction_items.owner_user_id','=',$acct_id)
                ->where(function ($query) {
                    $query->where('auction_items.product_status', '=', 'live')
                          ->orWhere('auction_items.product_status', '=', 'ended')
                          ->orWhere('auction_items.product_status', '=', 'unfulfilled')
                          ->orWhere('auction_items.product_status', '=', 'post')
                          ->orWhere('auction_items.product_status', '=', 'negotiations');
                })
               // ->where(function ($query) { // pinakalatest  bid ng kukunin ko.
               //     $query->where('bidding.status', '=', 'active')
               //           ->max('bidding.id');
               // })
                ->where('auction_items.status','!=','deleted')
                ->select(
                
                    'auction_items.product_url as product_url',
                    'auction_items.watch_lot_id',
                    'auction_items.id',
                    'auction_items.id as auction_id',
                    'auction_items.product_id',
                    'auction_items.bidding_title',
                    'auction_items.watch_brand',
                    'auction_items.reserves',
                    'auction_items.bidding_date',
                    'auction_items.bidding_time',
                    'auction_items.watch_photos',
                    'auction_items.owner_user_id',
                    'auction_items.product_status',
                    //'bidding.bid_amt',
                    'fulfillment.step2_fedx_tracking_number',
                    'fulfillment.step2_fedx_tracking_status',
                    'fulfillment.steps',
                    'fulfillment.id as fulfillment_id',
                    'fulfillment.step4_upload_authenticated_file',
                    'fulfillment.step5_buyer_payment',
                    'fulfillment.step6_payment_date',
                    
                    
                )
                ->get();

                 
                // get hightest bidding data
                if($items){ // if bidding item has entry
                   
                    foreach( $items as $item ) {
                            $bidding_item[$item->watch_lot_id] = DB::table('bidding')
                            ->where('lot_id', $item->watch_lot_id)
                            ->select(
                                'bid_amt',
                                'acct_id',
                                'id',
                            )
                            ->latest()
                            ->first();
                    }
                    
                }


                 // get negotionation data
                 if($items){ // if negotionation item has entry
                   
                    foreach( $items as $item ) {
                            $negotiations[$item->auction_id] = DB::table('negotiations')
                            ->where('auction_id', $item->auction_id)
                            ->select('*')
                            ->latest()
                            ->first();
                    }
                    
                }

               

            } else {

                $items = DB::table('auction_items')
                ->leftJoinSub($latestBid, 'bidding', function ($join) {
                    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
                })
                //->leftJoinSub('fulfillment', function ($join) {
                //    $join->on('auction_items.product_id', '=', 'fulfillment.product_id');
                //})
                ->where('.auction_items.owner_user_id','=',$acct_id)
                ->where('.auction_items.product_status','=',$product_status)
                ->where('auction_items.status','!=','deleted')
                ->select(
                
                    'auction_items.product_url as product_url',
                    'auction_items.watch_lot_id',
                    'auction_items.bidding_title',
                    'auction_items.reserves',
                    'auction_items.bidding_date',
                    'auction_items.bidding_time',
                    'auction_items.watch_photos',
                    'auction_items.product_status',
                    'auction_items.id as auction_id',
                    //'fulfillment.step2_fedx_tracking_number',
                    //'fulfillment.step2_fedx_tracking_status',
                    'bidding.bid_amt',
                    //'bidding.id as bidding_id'
                    
                )
                ->get();

            }
           


       }
       
        


       /*
        $items = AuctionItems::select(
                 'auction_items.product_url',
                 'auction_items.watch_lot_id',
                 'auction_items.bidding_title',
                 'auction_items.reserves',
                 'auction_items.bidding_date',
                 'auction_items.bidding_time',
                 'auction_items.watch_photos',
                 'bidding.bid_amt')

                ->selectRaw('bidding.bid_amt')
                 ->leftJoin('bidding', 'auction_items.watch_lot_id', '=', 'bidding.lot_id')
                ->where('auction_items.owner_user_id','=',$acct_id)
                ->where('auction_items.status','!=','deleted')
                ->where('auction_items.product_status','=',$product_status)
                ->groupby('auction_items.product_url')
                ->groupby('auction_items.watch_lot_id')
                ->groupby('auction_items.bidding_title')
                ->groupby('auction_items.reserves')
                ->groupby('auction_items.bidding_date')
                ->groupby('auction_items.bidding_time')
                ->groupby('auction_items.watch_photos')
                ->groupby('bidding.bid_amt')
                ->get();
        */
       /*
        $items = AuctionItems::leftJoin('bidding', function($join) {
            $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
          })->where('auction_items.owner_user_id','=',$acct_id)
          ->where('auction_items.status','!=','deleted')
         ->where('auction_items.product_status','=',$product_status)
          //->orderBy('bidding.bid_amt','desc')
          ->orderBy('auction_items.created_at','desc')
          ->get([
            'auction_items.product_url',
            'auction_items.watch_lot_id',
            'auction_items.bidding_title',
            'auction_items.reserves',
            'auction_items.bidding_date',
            'auction_items.bidding_time',
            'auction_items.watch_photos',
            'bidding.bid_amt',
          ]);
        */

        //var_dump($items);
        $items2 = AuctionItems::select('*');
        $items2 = $items2->where('owner_user_id','=',$acct_id);
        $items2 = $items2->where('status','!=','deleted');
        if($product_status){
            $items2 = $items2->where('product_status','=',$product_status);
        }
       
        $items2 = $items2->count();

        //AuctionItems::where('id', 1)->count();

        //$fulfillment = Fulfillment::select('*');
        //$fulfillment = $fulfillment->where('product_id','=',$watch_lot_id);
        //$fulfillment = $fulfillment->where('auction_id','=',$watch_lot_id);
        //$fulfillment = $fulfillment->where('acct_id','=',$acct_id);
        //$fulfillment = $fulfillment->where('status','!=','deleted');
        //$fulfillment = $fulfillment->first();
       

        if(isset($items)){
           $status= 'success';
           //$items =  implode(",", $items);
        } else {
            $status= 'failed';
           
        }

        //query likes // pass this like count
        $likes = array();
        if(isset($items)){
        foreach($items as $item) {

            $likes[$item->auction_id] = DB::table('favorites')
            ->where('auction_id','=', $item->auction_id)
            ->where('like','=','1')
            ->get()
            ->count();

            }
        }


        //echo json_encode(["status"=>'success','msg'=>'TEST','items'=>$items]);
        $data = [
            "status"=>$status,
            'msg'=>$status,
            "items"=>$items,
            "count"=>$items2,
            "likes"=>$likes,
            "bidding_items"=>$bidding_item,
            "negotiations"=>$negotiations,
            //"fulfillment"=>$fulfillment_data,
        ];
        return response()->json($data);


        /*
        // get bidding
        $bids = Bidding::select('*');
        $bids = $bids->where('lot_id','=',$watch_lot_id);
        $bids = $bids->where('status','!=','deleted');
        $bids = $bids->get();

        // get Comments
        $comments = Comments::select('*');
        $comments = $comments->where('lot_id','=',$watch_lot_id);
        $comments = $comments->where('status','!=','deleted');
        $comments = $comments->get();
        */
       /* echo json_encode([
            "status"=>$msg,
            'msg'=>'Item to list',
            "item"=>$item,
            //"bids"=>isset($bids)?$bids:'No bids yet',
            //"comments"=>isset($comments)?$comments:'No comment yet'
        ]);
        */
  
    }


    public function getFrontAuction($type=null){

        $offset=10;
        $limit=10;
         // if live or completed
         $latestAuction = DB::table('bidding')
        ->select('lot_id', DB::raw('MAX(bid_amt) as bid_amt'))
        ->where('status','!=','deleted')
        ->groupBy('lot_id');


        if($type == 'live' || $type == 'post'){ // get live and post auction

                $items = DB::table('auction_items')
                ->leftJoinSub($latestAuction, 'bidding', function ($join) {
                    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
                })
                //->where('.auction_items.owner_user_id','=',$acct_id)
                //->where('.auction_items.product_status','=','live')
                ->where('auction_items.product_status','=',$type)
                ->where('auction_items.status','!=','deleted')
                ->select(
                    'auction_items.product_url as product_url',
                    'auction_items.watch_lot_id',
                    'auction_items.product_id',
                    'auction_items.bidding_title',
                    'auction_items.reserves',
                    'auction_items.bidding_date',
                    'auction_items.bidding_time',
                    'auction_items.watch_photos',
                    'auction_items.id as auction_id',
                    'bidding.bid_amt'
                )
            //->groupby('watch_lot_id')
                ->orderBy('auction_items.bidding_date', 'asc')
                //->orderBy('auction_items.bidding_time', 'desc')
                //->offset($offset)
            ->limit($limit)
            ->get();






        } else if( $type == 'ending' ) { //get ending auction
               
                $fiveDaysAgoDate = now()->addDays(-5);
                $items = DB::table('auction_items')
                ->leftJoinSub($latestAuction, 'bidding', function ($join) {
                    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
                })
                //->where('.auction_items.owner_user_id','=',$acct_id)
                //->where('.auction_items.product_status','=','live')
                ->where('auction_items.bidding_date','>', $fiveDaysAgoDate->format('Y-m-d'))
                ->where('auction_items.product_status','=','live')
                ->where('auction_items.status','!=','deleted')
                ->select(
                    'auction_items.product_url as product_url',
                    'auction_items.watch_lot_id',
                    'auction_items.product_id',
                    'auction_items.bidding_title',
                    'auction_items.reserves',
                    'auction_items.bidding_date',
                    'auction_items.bidding_time',
                    'auction_items.watch_photos',
                    'auction_items.id as auction_id',
                    'bidding.bid_amt'
                )
            //->groupby('watch_lot_id')
                ->orderBy('auction_items.bidding_date', 'asc')
                //->orderBy('auction_items.bidding_time', 'desc')
                //->offset($offset)
            ->limit($limit)
            ->get();

        } else if($type == 'upcomming') {

                $items = DB::table('auction_items')
                ->leftJoinSub($latestAuction, 'bidding', function ($join) {
                    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
                })
                //->where('.auction_items.owner_user_id','=',$acct_id)
                //->where('.auction_items.product_status','=','live')
                ->where('auction_items.product_status','=','pending')
                ->where('auction_items.bidding_title','!=','')
                ->where('auction_items.bidding_date','!=','')
                ->where('auction_items.status','!=','deleted')
                ->select(
                    'auction_items.product_url as product_url',
                    'auction_items.watch_lot_id',
                    'auction_items.product_id',
                    'auction_items.bidding_title',
                    'auction_items.reserves',
                    'auction_items.bidding_date',
                    'auction_items.bidding_time',
                    'auction_items.watch_photos',
                    'auction_items.id as auction_id',
                    'bidding.bid_amt'
                )
            //->groupby('watch_lot_id')
                ->orderBy('auction_items.bidding_date', 'asc')
                //->orderBy('auction_items.bidding_time', 'desc')
                //->offset($offset)
            ->limit($limit)
            ->get();

        }

       //query likes // pass this like count
       $likes = array();
       if(isset($items)){
        foreach($items as $item) {

            $likes[$item->auction_id] = DB::table('favorites')
            ->where('auction_id','=', $item->auction_id)
            ->where('like','=','1')
            ->get()
            ->count();

             }
        }
       


       // $itemscount = $items->count();
        
        //var_dump($items);

        if(isset($items)){
           $status= 'success';
           //$items =  implode(",", $items);
        } else {
            $status= 'failed';
           
        }
        
        $data = [
            "status"=>$status,
            'msg'=>$status,
            "items"=>$items,
            "likes"=>$likes,
            //"count"=>$itemscount,
        ];
        return response()->json($data);


    }


    public function updateAuctionStatusToEnded($product_id= null) {

        $status = 'failed';
        $post_type = '';
        $response = 'failed to updated "ended status" to shopify';
         if($product_id){
            
              
                // get auctions attribute
                $item = DB::table('auction_items')
                ->where('product_id', $product_id)
                ->select(
                    'id',
                    'watch_lot_id',
                    'reserves',
                    'bidding_title',
                    'owner_user_id',
                    'bidding_date'
                )
                ->where('product_status', '=', 'live')
                ->where('status', '!=', 'deleted')
                ->first();

                if(!$item){ // stop anything when product status is no longer live

                    $data = [
                        "post_type"=>$post_type,
                        "status"=>'not-live',
                        "response"=>'live auction no longer exist',
                    ];
            
                    
                    return response()->json($data);
                }

                $bid = DB::table('bidding')
                ->where('lot_id', $item->watch_lot_id)
                //->where('bid_amt', '>=', $item->reserves)
                ->select(
                    'bid_amt',
                    'id'
                )
                //->orderBy('id', 'desc')
                ->latest()
                ->first();


                if(!$bid){ // if no bidding on this item
                   
                     //move auction to post/marketplace status
                     DB::table('auction_items')
                     ->where('product_id', $product_id)
                     ->update(['product_status' => 'post']);

                         //update shopify meta fields to post
                         $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($product_id,'bidding_status');
                         $response = $this->shopifyService->updateMeta(
                             $product_id,
                             $bidding_status_meta_id,
                             'bidding_status',
                             'post',
                             'single_line_text_field'
                         );
                     
                     $post_type = 'MARKET PLACE';


                    return false;
                }

                // check if bid existed AND HIGHER THAN RESERVES
                if($bid->bid_amt >= $item->reserves) { 
                
                    // if bid is higher that reserve then set winner
                    /* Congratz you are the winner */

                    $bidding = Bidding::find($bid->id);
                    $bidding->bid_status = 'win';
                    $bidding->save();

                    // move auction item to unfulfilled
                    DB::table('auction_items')
                    ->where('product_id', $product_id)
                    ->update(['product_status' => 'unfulfilled']);
            
                    //update shopify meta fields to unfulfilled
                    $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($product_id,'bidding_status');
                    $response = $this->shopifyService->updateMeta(
                        $product_id,
                        $bidding_status_meta_id,
                        'bidding_status',
                        'unfulfilled',
                        'single_line_text_field'
                    );


                    /* Update if existing */
                    if(!DB::table('fulfillment')
                    ->where('product_id', $product_id)
                   // ->where('auction_id', $acct_id)
                    //->where('acct_id', $acct_id)
                    ->update([
                        'step1_shipping' => 'No',
                        'steps' => '1',
                        'lot_id' => $item->watch_lot_id,
                        //'product_id' => 'unfulfilled'
                        'auction_id' => $item->id,
                        'buyer_id' => $bidding->acct_id,
                        'acct_id' => $item->owner_user_id,
                        'fulfillment_status' => 'active',
                        'status' => 'active'
                        ])){
                            //insert data
                            $fulfillment = new Fulfillment();
                            $fulfillment->step1_shipping = 'No';
                            $fulfillment->steps = 1;
                            $fulfillment->lot_id = $item->watch_lot_id;
                            $fulfillment->product_id = $product_id;
                            $fulfillment->auction_id = $item->id;
                            $fulfillment->buyer_id = $bidding->acct_id; //get bidder ID
                            $fulfillment->acct_id = $item->owner_user_id;
                            $fulfillment->fulfillment_status = 'active';
                            $fulfillment->status = 'active';
                            $fulfillment->save();

                    }


                 //sending email
                 //send email using template 6

                 $auction_e = DB::table('auction_items')
                 ->where('id',$item->id)
                 ->select(
                     'id',
                     'watch_lot_id',
                     'reserves',
                     'bidding_title',
                     'owner_user_id',
                     'bidding_date'
                 )
                 //->where('product_status', '=', 'live')
                 ->where('status', '!=', 'deleted')
                 ->first();

                 $bidder_id =  Bidders::where('acct_id',  $auction_e->acct_id)->first()->id;
                 $bidder = Bidders::find($bidder_id);
                 $response['shopify_product_url'] = 'https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction_e->product_url; 
                 
                 $originalDate = $auction_e->bidding_date;
                 // Unix time = 1685491200
                 $unixTime = strtotime($originalDate);
                 // Pass the new date format as a string and the original date in Unix time
                 $newDate = date("F j, Y", $unixTime);
                 // echo $newDate;

                 $email = array(
                     'email_code'=>'6',
                     'seller_name'=>$bidder->name,
                     'seller_email'=>$bidder->email,
                     'lot_highest_bid_price'=>$bidding->bid_amt,
                     'lot_number'=>$lotid,
                     'lot_name'=>$auction_e->bidding_title,
                     'lot_sold_price'=>$bidding->bid_amt, //get highest bid
                     'lot_ending_time'=>$newDate,
                     'here'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">here</a>',
                     
                 );
                 $this->sendingEmail->html_bidder_email($email);
                 $this->sendingEmail->html_admin_email($email);
                    
                
                 //sending email
                 //send email using template 7
                 $auction_e = DB::table('auction_items')
                 ->where('id',$item->id)
                 ->select(
                     'id',
                     'watch_lot_id',
                     'reserves',
                     'bidding_title',
                     'owner_user_id',
                     'bidding_date'
                 )
                 //->where('product_status', '=', 'live')
                 ->where('status', '!=', 'deleted')
                 ->first();

                 $bidder_id =  Bidders::where('acct_id',  $auction_e->acct_id)->first()->id;
                 $bidder = Bidders::find($bidder_id);
                 $response['shopify_product_url'] = 'https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction_e->product_url; 
                 
                 $originalDate = $auction_e->bidding_date;
                 // Unix time = 1685491200
                 $unixTime = strtotime($originalDate);
                 // Pass the new date format as a string and the original date in Unix time
                 $newDate = date("F j, Y", $unixTime);
                 // echo $newDate;

                 $email = array(
                     'email_code'=>'7',
                     'seller_name'=>$bidder->name,
                     'seller_email'=>$bidder->email,
                     'lot_highest_bid_price'=>$bidding->bid_amt,
                     'lot_number'=>$lotid,
                     'lot_name'=>$auction_e->bidding_title,
                     'lot_sold_price'=>$bidding->bid_amt, //get highest bid
                     'lot_ending_time'=>$newDate,
                     'here'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">here</a>',
                     
                 );
                 $this->sendingEmail->html_bidder_email($email);
                 $this->sendingEmail->html_admin_email($email);




                
                $post_type = 'UNFULFILLED';
                

                } else {

                    /*
                    $bid = DB::table('bidding')
                    ->where('lot_id', $item->watch_lot_id)
                    ->select(
                        'bid_amt',
                        'id'
                    )
                    //->orderBy('id', 'desc')
                    ->latest()
                    ->first();
                    */

                    //if(count($bid)==0){ // if bidding is zero auction item will go to market place
                        /*   
                        //move auction to post/marketplace status
                        DB::table('auction_items')
                        ->where('product_id', $product_id)
                        ->update(['product_status' => 'post']);

                            //update shopify meta fields to post
                            $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($product_id,'bidding_status');
                            $response = $this->shopifyService->updateMeta(
                                $product_id,
                                $bidding_status_meta_id,
                                'bidding_status',
                                'post',
                                'single_line_text_field'
                            );
                        
                        $post_type = 'MARKET PLACE';

                        //sending email
                        //send email using template 4
                        $bidder_id =  Bidders::where('acct_id',  $item->owner_user_id)->first()->id;
                        $bidder = Bidders::find($bidder_id);
                        $response['shopify_product_url'] = 'https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$item->product_url; 
                        
                        $originalDate = $item->bidding_date;
                        // Unix time = 1685491200
                        $unixTime = strtotime($originalDate);
                        // Pass the new date format as a string and the original date in Unix time
                        $newDate = date("F j, Y", $unixTime);
                        // echo $newDate;

                        $email = array(
                            'email_code'=>'4',
                            'seller_name'=>$bidder->name,
                            'seller_email'=>$bidder->email,
                            'lot_highest_bid_price'=>$bidding->bid_amt,
                            'lot_number'=>$lotid,
                            'lot_name'=>$item->bidding_title,
                            'lot_sold_price'=>$item->reserves,
                            'lot_ending_time'=>$newDate,
                            'here'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">here</a>',
                            
                        );
                        $this->sendingEmail->html_bidder_email($email);
                        $this->sendingEmail->html_admin_email($email);

                        */

                    //} else { //auction item will go to negotations


                         //move auction to post/marketplace status
                         DB::table('auction_items')
                         ->where('product_id', $product_id)
                         ->update(['product_status' => 'negotiations']);
 
                             //update shopify meta fields to post
                             $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($product_id,'bidding_status');
                             $response = $this->shopifyService->updateMeta(
                                 $product_id,
                                 $bidding_status_meta_id,
                                 'bidding_status',
                                 'negotiations',
                                 'single_line_text_field'
                             );
                         

                            $post_type = 'NEGOTIATIONS';
 




                   // }


                        




                        




                    
                }


            $status = 'success';
            $access = 'accepted';

                
          
                 
            

         }

        


        $data = [
            "post_type"=>$post_type,
            "status"=>$status,
            "response"=>$response,
        ];

        
        return response()->json($data);

    }



    public function updateAuctionStatusToPost($acct_id=null, $product_id= null) {

        $status = 'failed';
        $response = 'failed to updated "post status" to shopify';
        $isLoggedIn = $this->shopifyService->checkIfUserLoggedIn($acct_id);

         if( $product_id && !empty($isLoggedIn) ){
            if( DB::table('auction_items')
                ->where('product_id', $product_id)
                ->update(['product_status' => 'post'])) {
                   
                    //update shopify biddig status
                
                    $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($product_id,'bidding_status');
                    $response = $this->shopifyService->updateMeta(
                        $product_id,
                        $bidding_status_meta_id,
                        'bidding_status',
                        'post',
                        'single_line_text_field'
                    );
          
                    $status = 'success';
            } 

         }

        


        $data = [
            "status"=>$status,
            "response"=>$response,
        ];

        
        return response()->json($data);

    }


    public function updateAuctionStatusToUnfulfilled($acct_id=null,$product_id= null) {

        $access = 'denied';
        $status = 'failed';
        $response = 'failed to updated "unfulfilled status" to shopify';

        $isLoggedIn = $this->shopifyService->checkIfUserLoggedIn($acct_id);

         if($product_id && !empty($isLoggedIn) ){


            // update auction status
            if( DB::table('auction_items')
                ->where('product_id', $product_id)
                ->update(['product_status' => 'unfulfilled'])) {
                   
                    //update shopify biddig status
                    $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($product_id,'bidding_status');
                    $response = $this->shopifyService->updateMeta(
                        $product_id,
                        $bidding_status_meta_id,
                        'bidding_status',
                        'unfulfilled',
                        'single_line_text_field'
                    );

                    $response = 'Item to updated "unfulfilled status" to shopify';
                    

                    // update bidding status to win
                    $item = DB::table('auction_items')
                    ->rightJoin('bidding', 'auction_items.watch_lot_id', '=', 'bidding.lot_id')
                    ->where('auction_items.status','!=','deleted')
                    ->where('auction_items.product_id','=',$product_id)
                    ->where('bidding.status','!=','deleted')
                    //->max('bidding.bid_amt')
                    ->select(
                        'bidding.bid_amt',
                        'bidding.id'
                    )
                    ->orderBy('bidding.bid_amt', 'desc')
                    ->first();
                    //$item->update(['bidding.bid_status' => 'win']);
    
                    $bidding = Bidding::find($item->id);
                    $bidding->bid_status = 'win';
                    if($bidding->save()){
    
                        
                         $status = 'success';
                         $access = 'accepted';
                      
                    } else {

                        $access = 'accepted';
                        $status = 'failed to save win wording';
    
                    }
    
         
          
                    
            } 

        }

        


        $data = [
            "status"=>$status,
            "response"=>$response,
            "access"=>$isLoggedIn,
        ];

        
        return response()->json($data);

    }



    public function getMyWins($acct_id=null,$product_status=null,$lot_id=null){

        
     
        $items = DB::table('bidding')
                ->leftJoin('auction_items', 'bidding.lot_id', '=', 'auction_items.watch_lot_id')
                ->leftJoin('fulfillment', 'fulfillment.product_id', '=', 'auction_items.product_id')
                ->where('auction_items.status','!=','deleted')
                ->where('bidding.status','!=','deleted')
                ->where('bidding.acct_id','=',$acct_id)
                //->where('bidding.lot_id','=',$lot_id)
                ->where('bid_status','=','win')
                ->get( ['auction_items.product_url as product_url',
                'auction_items.watch_lot_id',
                'auction_items.id',
                'auction_items.product_id',
                'auction_items.bidding_title',
                'auction_items.reserves',
                'auction_items.bidding_date',
                'auction_items.bidding_time',
                'auction_items.watch_photos',
                'auction_items.owner_user_id',
                'auction_items.product_status',
                'bidding.bid_amt',
                'fulfillment.step2_fedx_tracking_number',
                'fulfillment.step2_fedx_tracking_status',
                'fulfillment.steps',
                'fulfillment.id as fulfillment_id',
                'fulfillment.step4_upload_authenticated_file',
                'fulfillment.step5_buyer_payment'
                 ]);
                /*'auction_items.product_url',
                'auction_items.watch_lot_id',
                'auction_items.product_id',
                'auction_items.bidding_title',
                'auction_items.reserves',
                'auction_items.bidding_date',
                'auction_items.bidding_time',
                'auction_items.watch_photos',
                'auction_items.product_status',
                'bidding.bid_amt'*/
         
        if(isset($items)){
            $status= 'success';
            //$items =  implode(",", $items);
         } else {
             $status= 'failed';
            
         }
         //echo json_encode(["status"=>'success','msg'=>'TEST','items'=>$items]);
         $data = [
            "status"=>$status,
            'msg'=>$status,
            "items"=>$items,
            //"count"=>$items2,
        ];
        return response()->json($data);

        


    }








       

    

}
