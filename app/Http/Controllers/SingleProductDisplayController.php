<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\User;
use App\Models\AuctionItems;
use App\Models\Bidders;
use App\Models\Bidding;
use App\Models\Comments;
use App\Models\Fulfillment;


class SingleProductDisplayController extends Controller
{
    //

    public function getProduct($watch_lot_id=null){
            
            // get item
            $item = AuctionItems::select('*');
            $item = $item->where('watch_lot_id','=',$watch_lot_id);
            $item = $item->where('status','!=','deleted');
            //$item = $item->where('product_status','=','pending');
            //$item = $item->where('product_status','=','pending');
            $item = $item->first();
            //$reserves = $item->reserves;
            $owner_user_id = $item->owner_user_id;

            $bidding_date = $item->bidding_date;
            $bidding_time = $item->bidding_time;
            $data_time = $bidding_date.' '.$bidding_time;
            //get
            $hours_remaining = $data_time;
            //if( !empty($bidding_date) && !empty($data_time)) {
               /*  var_dump($data_time);
                $data_time = strtotime($data_time);
                var_dump($data_time);
                var_dump(time());
                $timestamp = time();
                $currentDate = gmdate('Y-m-d', $timestamp);
                $currentDate = strtotime($currentDate);
                $remaining = $data_time - $currentDate;
                $days_remaining = floor($remaining / 86400);
                $hours_remaining = floor(($remaining % 86400) / 3600);
                */
               // $timeFirst  = strtotime('2011-05-12 18:20:20');
              //  $timeSecond = strtotime('2011-05-13 18:20:20');
              //  $hours_remaining = $timeSecond - $timeFirst;
                //echo "There are $days_remaining days and $hours_remaining hours left";

            //}
           



            if($item){
               $item_data = $item;
            } else {
                $item_data = 'No item found';
            }
            
            // get latest biddig ammount
            $bid_amt = Bidding::select('*');
            $bid_amt = $bid_amt->where('lot_id','=',$watch_lot_id);
            $bid_amt = $bid_amt->where('status','!=','deleted');
            $bid_amt = $bid_amt->latest();
            $bid_amt = $bid_amt->first();
            $bid_amt = isset($bid_amt->bid_amt)?$bid_amt->bid_amt:'';

            if($bid_amt){
                $bid_amt_data = $bid_amt;
             } else {
                 $bid_amt_data = 0  ;
             }
            
             
            // get all bidding 
            /*$bids = Bidding::select('*');
            $bids = $bids->where('lot_id','=',$watch_lot_id);
            $bids = $bids->where('status','!=','deleted');
            $bids = $bids->orderBy('bid_amt','desc');
            $bids = $bids->get();*/
            $bids = Bidding::leftJoin('bidders', function($join) {
                $join->on('bidding.acct_id', '=', 'bidders.acct_id');
              })
              ->where('bidding.lot_id','=',$watch_lot_id)
              ->where('bidding.status','!=','deleted')
              //->orderBy('bidding.bid_amt','desc')
              ->orderBy('bidding.created_at','desc')
              ->get([
                  'bidding.bid_amt',
                  'bidding.created_at',
                  'bidders.user_name',
              ]);


             if($bids){
                $bids_data = $bids;
             } else {
                 $bids_data = 'No item found';
             }



            // get Comments
            /*
            $comments = Comments::select('*');
            $comments = $comments->where('lot_id','=',$watch_lot_id);
            $comments = $comments->where('status','!=','deleted');
            $comments = $comments->orderBy('created_at','desc');
            $comments = $comments->get();
            */
            $comments = Comments::leftJoin('bidders', function($join) {
                $join->on('comments.acct_id', '=', 'bidders.acct_id');
              })
              ->where('comments.lot_id','=',$watch_lot_id)
              ->where('comments.status','!=','deleted')
              //->orderBy('bidding.bid_amt','desc')
              ->orderBy('comments.created_at','desc')
              ->get([
                  'comments.comment',
                  'comments.created_at',
                  'bidders.user_name',
              ]);

              
              $seller = Bidders::select('*');
              $seller = $seller->where('acct_id','=',$owner_user_id);
              $seller = $seller->where('status','!=','deleted');
              $seller = $seller->first();
             
              

            
            if($comments){
                $comments_data = $comments;
             } else {
                 $comments_data = 'No item found';
             }

            /*
            $fulfillment = Fulfillment::select('*');
            $fulfillment = $fulfillment->where('product_id','=',$watch_lot_id);
            $fulfillment = $fulfillment->where('auction_id','=',$watch_lot_id);
            $fulfillment = $fulfillment->where('acct_id','=',$watch_lot_id);
            $fulfillment = $fulfillment->where('status','!=','deleted');
            $fulfillment = $fulfillment->first();
            */
           /*
            if($fulfillment){
                $fulfillment_data = $fulfillment;
             } else {
                 $fulfillment_data = 'No item found';
             }*/
           
            $data = [
                "status"=>'success',
                'msg'=>'Failed to list',
                "item"=>$item_data,
                //"fulfillment"=>$fulfillment_data,
                "highest_bid_amt"=>$bid_amt_data,
                "bids"=>$bids_data,
                "comments"=>$comments_data,
                "seller"=>$seller,
                "seconds"=>$hours_remaining
            ];
            

            //die();
            /*
            $data = [
                'product' => 'Example Product',
                'price' => 100,
                'description' => 'This is an example product.'
            ];
             */
            return response()->json($data);
        
      
    }


    

}
