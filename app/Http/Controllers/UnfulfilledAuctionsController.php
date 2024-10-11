<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;
use  URL;
//use ZipArchive;

use App\Services\ShopifyService;
use App\Models\User;
use App\Models\AuctionItems;
use App\Models\Bidders;
use App\Models\Bidding;
use App\Models\Fulfillment;



class UnfulfilledAuctionsController extends Controller
{
    protected $shopifyService;
    public $lot;
    public $bid;
    public $b;
    public $sendingEmail;
 
     public function __construct(EmailTemplateController $emaitemplate, ShopifyService $shopifyService, AuctionItems $lot, Bidders $bidders,Bidders $bidder  )
     {
         $this->shopifyService = $shopifyService;
         $this->lot = $lot;
         $this->bid = $bidders;
         $this->b = $bidder;
         $this->sendingEmail = $emaitemplate;
     }
         
         public $client;
         
        
 
    
       public function new($id=null){
             
             $page = array(
                 'menu'=>'unfulfilled',
                 'page_title'=>'Unfulfilled Auction Listing',
                 'subtitle'=>'Unfulfilled Auction Entry',
                 'status'=>'unfulfilled-auctions',
                 //'bidders'=>$this->bid->getBidders(),
                 'bidders2'=>app('App\Http\Controllers\BiddersController')->getBillders2()
                
             );



           
             if($id){
                 $auction = AuctionItems::find( $id );

                 $buyer = DB::table('bidding')
                 ->select('acct_id', DB::raw('MAX(bid_amt) as bid_amt'))
                 ->where('status','!=','deleted')
                 ->where('lot_id','=',$auction->watch_lot_id)
                // ->where('status','!=','deleted')
                 ->groupBy('acct_id')
                 ->get();

                 $fulfillment = DB::table('fulfillment')
                 ->where('status','!=','deleted')
                 ->where('fulfillment_status','=','active')
                 ->where('lot_id','=',$auction->watch_lot_id)
                 ->first();
                 
                 //var_dump($fulfillment);


                 $page['auction'] = $auction;
                 $page['fulfillment'] = $fulfillment;
                 $page['subtitle'] = 'Fulfillment Status';
                 $page['bidderD'] = $this->b->getBidder($auction->owner_user_id);
                 $page['buyer'] = $this->b->getBidder($buyer[0]->acct_id);
                 $page['product_url'] = '<a href="//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url.'" target="_blank" class="btn btn-warning">View Shopify</a>'; 
 
             }
          
             return view('admin.unfulfilled-auctions-new')->with('page',$page);
 
       }
       
     
       
 
       public function get(){
 
         $search_this = $_GET['search']['value'];
         $start = $_GET['start'];
         $length = $_GET['length'];
         $orderby = $_GET['order'][0]['column'];
         $orderdir = $_GET['order'][0]['dir'];
         
         
         $auctions = AuctionItems::select('*');
         $auctions->where('status','!=','deleted');
         $auctions->where('product_status','=','unfulfilled');
         //$users->where('branch_id','=',$branch_id);
         $auctions->orderBy('watch_lot_id', 'desc');
        
 
     
         $auctions2 = AuctionItems::select(array('id'));
         //$users2->where('branch_id','=',$branch_id);
         $auctions2->where('status','!=','deleted');
         $auctions2->where('product_status','=','unfulfilled');
       
         if ($search_this) {
                     
                     //$users->where('watch_lot_id','like', '%'.strtolower($search_this).'%');
                     $auctions->where('watch_lot_id','=',$search_this);
 
         }
 
         $auctions_count = $records_filtered = $auctions->get()->count();
         $records_filtered = $auctions->count();
         if ($search_this) {
             $records_filtered = $auctions->count();
         }
 
         if($orderby == 1)
             $orderby = 'name';
         else
             $orderby = 'id';
         
         $auctions = $auctions
             ->orderBy($orderby, $orderdir)
             ->skip( $start )->take($length)
             ->get();
         
         $data = array();
 
         if ($auctions)
         {
               
               foreach($auctions as $k=>$auction) {
                
                 // get latest biddig ammount
                 $newbidamt = number_format(0,2);
                 $bid_amt = Bidding::select('bid_amt');
                 $bid_amt = $bid_amt->where('lot_id','=',$auction->watch_lot_id);
                 $bid_amt = $bid_amt->where('status','!=','deleted');
                 $bid_amt = $bid_amt->latest();
                 $bid_amt = $bid_amt->first();
                if(isset($bid_amt->bid_amt) && $bid_amt->bid_amt>0){
                  $newbidamt = number_format($bid_amt->bid_amt,2);
 
                }
                 //$bid_amt = (isset($bid_amt->bid_amt))?'$'.$bid_amt->bid_am:'--';
 
 
                 $viewlineshopify = '';
                 if (isset($auction->product_url)){
                     $viewlineshopify = '<a style="margin-bottom:5px;" class="btn btn-warning" target="_blank" href="https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url.'">View Display Shopify</a>&nbsp;';
                     $viewlineshopify .= '<a style="margin-bottom:5px;" class="btn btn-warning" target="_blank" href="//'.env("SHOPIFY_STORE_BACKEND_ADMIN").'/products/'.$auction->product_id.'">View Admin Shopify</a>&nbsp;'; 
                 }
                  $btn = '<!--<a class="btn  btn-primary btn-md btn-edit"  href="'.url('completed/view/'.$auction->id).'"><i class="fa fa-pencil" aria-hidden="true"></i>View</a>&nbsp;-->'.$viewlineshopify.'<a style="margin-bottom:5px;" class="btn  btn-success btn-md btn-edit"  href="'.url('unfulfilled-auctions/edit/'.$auction->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> View</a>&nbsp;<a style="margin-bottom:5px;" class="btn  btn-danger btn-md btn-delete"  data-id="'.$auction->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                 
                 
                 array_push($data,array(
                         $auction->id,
                         'Lot#'.$auction->watch_lot_id,
                         $auction->bidding_title,
                         //'$'.$newbidamt,
                         isset($auction->product_status)?ucfirst($auction->product_status):'----',
                         date('m/d/Y', strtotime($auction->created_at)),
                         date('m/d/Y', strtotime($auction->bidding_date)),
                         date('m/d/Y', strtotime($auction->updated_at)),
                         $btn,
                         
                     ));
               }
               
               
         }
         echo json_encode(array('draw'=>$_GET['draw'],'recordsTotal'=>$auctions_count,'recordsFiltered'=>$records_filtered,'data'=>$data));
         
         
       }
 
       public function delete($id) {
           
         $auction =  AuctionItems::find($id);
         $auction->status = 'deleted';

         //set shopify product as draft
        $productData = [
            'status' => 'draft',
        ];
        $this->shopifyService->updateProduct($auction->product_id,$productData);

        
          if($auction->save()) {
                echo json_encode(["msg"=>'success']);
           } else {
                 echo json_encode(["msg"=>'failed']);
          }
             
      }
 
       
      public function download($lotid=null,$pdf_filename=null){
            
        $path = public_path('authenticated/lot-'.$lotid.'/'.$pdf_filename); //download all images base from lotid
        return response()->download($path);
      }

      public function downloadPaymentFile($lotid=null,$pdf_filename=null){
            
        $path = public_path('paymentfile/lot-'.$lotid.'/'.$pdf_filename); //download all images base from lotid
        return response()->download($path);
      }
 
     

       public function store(Request $request) {
             

           
           
             
             if(request()->id){
               //update
               $item = Fulfillment::find(request()->id);
               //$lotid = request()->watch_lot_id;
              // $sucess_msg = 'Lot #'.$lotid.' updated successfully!';
            
               
             } else {
               //create
               $item = new Fulfillment();
               //$lotid = $this->lot->LotNumber();
               //$item->watch_lot_id = $lotid;
               //$sucess_msg = 'New Lot #'.$lotid.' added successfully!';
              
               //$message = 'Fulfillment added successfully!';
             }


             $pdf_auth_file = '';
             $lotid = isset(request()->lot_id)?request()->lot_id:'';
             if($request->hasFile('step4_upload_authenticated_file')) {
               
                $file = $request->file('step4_upload_authenticated_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('authenticated/lot-'.$lotid);
                $file->move($destinationPath, $filename);
                $pdf_auth_file = $filename;
                
            }
               
             
            
             if(request()->step1_shipping  != ''){
                //$item->step1_shipping = request()->step1_shipping;
                //$item->steps = 1;
             } 
             
             if(
                 
                request()->step2_fedx_tracking_number  != ''
                &&  request()->step2_fedx_tracking_status  != ''
                 ){

                //$item->step2_shipping_date = request()->step2_shipping_date;
                //$item->step2_fedx_tracking_number = request()->step2_fedx_tracking_number;
                //$item->step2_fedx_tracking_status = request()->step2_fedx_tracking_status;
                //$item->steps = 2;
             } 

             if( request()->step3_arrive_date  != '' && request()->step3_item_arrived  != '' ){

                $item->step3_arrive_date = request()->step3_arrive_date;
                $item->step3_item_arrived = request()->step3_item_arrived;
                $item->steps = 3;

                 //sending email
                 //send email using template 9

                 $auction = DB::table('auction_items')
                    ->where('id',$item->auction_id)
                    ->select(
                        'id',
                        'watch_lot_id',
                        'reserves',
                        'watch_brand',
                        'bidding_title',
                        'owner_user_id',
                        'bidding_date',
                        'product_url'
                    )
                    ->where('product_status', '=', 'unfulfilled')
                    ->where('status', '!=', 'deleted')
                    ->first();

                    $bidder_id =  Bidders::where('acct_id',  $item->acct_id)->first()->id;
                    $bidder = Bidders::find($bidder_id);
                    $response['shopify_product_url'] = 'https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url; 
                    
                    $originalDate = $auction->bidding_date;
                    // Unix time = 1685491200
                    $unixTime = strtotime($originalDate);
                    // Pass the new date format as a string and the original date in Unix time
                    $newDate = date("F j, Y", $unixTime);
                    // echo $newDate;

                    $email = array(
                        'email_code'=>'9',
                        'seller_name'=>$bidder->name,
                        'seller_email'=>$bidder->email,
                        'brand_name'=>$auction->watch_brand,
                        //'lot_highest_bid_price'=>$bidding->bid_amt,
                        'lot_number'=>$lotid,
                        'lot_name'=>$auction->bidding_title,
                        'lot_sold_price'=>$auction->reserves,
                        'lot_ending_time'=>$newDate,
                        'here'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">here</a>',
                        
                    );
                    $this->sendingEmail->html_bidder_email($email);
                    $this->sendingEmail->html_admin_email($email);


             }

             if(  request()->step4_test_date  != ''){

               // $item->step4_item_genuine = request()->step4_item_genuine;
                $item->step4_test_date = request()->step4_test_date;
                if($pdf_auth_file){
                    $item->step4_upload_authenticated_file = $pdf_auth_file;
                }
               
                $item->steps = 4;

                 //sending email
                 //send email using template 10

                 $auction = DB::table('auction_items')
                    ->where('id',$item->auction_id)
                    ->select(
                        'id',
                        'watch_lot_id',
                        'reserves',
                        'watch_brand',
                        'bidding_title',
                        'owner_user_id',
                        'bidding_date'
                    )
                    ->where('product_status', '=', 'unfulfilled')
                    ->where('status', '!=', 'deleted')
                    ->first();

                    $bidder_id =  Bidders::where('acct_id',  $item->acct_id)->first()->id;
                    $bidder = Bidders::find($bidder_id);
                    $response['shopify_product_url'] = url('public/authenticated/lot-'.$auction->watch_lot_id.'/'.$item->step4_upload_authenticated_file); 
                    
                    $originalDate = $auction->bidding_date;
                    // Unix time = 1685491200
                    $unixTime = strtotime($originalDate);
                    // Pass the new date format as a string and the original date in Unix time
                    $newDate = date("F j, Y", $unixTime);
                    // echo $newDate;

                    $email = array(
                        'email_code'=>'10',
                        'seller_name'=>$bidder->name,
                        'seller_email'=>$bidder->email,
                        'brand_name'=>$auction->watch_brand,
                        //'lot_highest_bid_price'=>$bidding->bid_amt,
                        'lot_number'=>$lotid,
                        'lot_name'=>$auction->bidding_title,
                        'lot_sold_price'=>$auction->reserves,
                        'lot_ending_time'=>$newDate,
                        'attach-pdf'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">Attached PDF</a>',
                        
                    );
                    $this->sendingEmail->html_bidder_email($email);
                    $this->sendingEmail->html_admin_email($email);


           





             }

             if( isset($item->step5_buyer_payment)) {
                //$item->step5_buyer_payment = request()->step5_buyer_payment;
                //$item->steps = 5;
                $item->steps = 5;
             } 

             if( isset($item->step6_payment_received) ){

                //$item->step6_payment_received = isset(request()->step6_payment_received)?request()->step6_payment_received:'';
                //$item->step6_payment_date = isset(request()->step6_payment_date)?request()->step6_payment_date:'';
                //$item->step6_payment_ref = isset(request()->step6_payment_ref)?request()->step6_payment_ref:'';
                 $item->steps = 6;
             } 

             if(request()->step7_ship_out  != '' && request()->step7_ship_out_date  != '' && request()->step7_ship_out_tracking_number  != ''){

                $item->step7_ship_out = isset(request()->step7_ship_out)?request()->step7_ship_out:'';
                $item->step7_ship_out_date = isset(request()->step7_ship_out_date)?request()->step7_ship_out_date:'';
                $item->step7_ship_out_tracking_number = isset(request()->step7_ship_out_tracking_number)?request()->step7_ship_out_tracking_number:'';
                $item->steps = 7;
            }
             $auction_id = isset(request()->auction_id)?request()->auction_id:'';
             $item->lot_id = isset(request()->lot_id)?request()->lot_id:'';
             $item->auction_id = isset(request()->auction_id)?request()->auction_id:'';
             $item->buyer_id = isset(request()->buyer_id)?request()->buyer_id:'';
             $item->acct_id = isset(request()->acct_id)?request()->acct_id:'';
             $item->fulfillment_status = isset(request()->fulfillment_status)?request()->fulfillment_status:'active';
             $item->status = 'active';
           
            
             $message = 'Fulfillment Step '.$item->steps.' updated successfully!';
            
             //if( $item->save() ){ // success
 
               // echo json_encode(["status"=>'success','msg'=>'Succesfully added']);
 
             //} else { 
                
                // echo json_encode(["status"=>'failed','msg'=>'Failed to list']);
                
 
            // }


             if( $item->save() ){ // success

                
                    return redirect('/unfulfilled-auctions/edit/'.$auction_id)->with('added', $message);
                

            } else { // failed
               
                    return redirect('/unfulfilled-auctions/edit/'.$auction_id)->with('failed', 'Fulfillment failed to update!');
              


            }
                 
             
 
       }
       
        public function index(){

            $page = array(
                'menu'=>'unfulfilled',
                'page_title'=>'Unfulfilled Auctions',
                'subtitle'=>'Unfulfilled Items',
                'status'=>'active',
            );

           /* $latestBid = DB::table('bidding')
            ->select('lot_id', DB::raw('MAX(bid_amt) as bid_amt'))
            ->where('status','!=','deleted')
            ->groupBy('lot_id');*/
            //$latestBid = Bidding::where('bid_amt');
           // $price = DB::table('bidding')->max('bid_amt');
           /* $latestPosts = DB::table('bidding')
            ->select('lot_id', DB::raw('MAX(bid_amt) as bid_amt'))
            ->where('status','!=','deleted')
            ->where('lot_id','=','095')
            ->groupBy('lot_id')->get();

            $latestPosts =  DB::table('bidding')->select('*')->where('lot_id','=','095')->max('bid_amt');
                var_dump($latestPosts);*/

             /*
            $items = DB::table('auction_items')
            ->leftJoinSub($latestBid, 'bidding', function ($join) {
                $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
            })
            ->where('auction_items.owner_user_id','=','7445465759788')
            //->where('auction_items.reserves','<=','bidding.bid_amt')
            //->where('auction_items.product_status','=',$product_status)
            ->where(function ($query) {
                $query->where('auction_items.product_status', '=', 'live')
                      ->orWhere('auction_items.product_status', '=', 'ended')
                      ->orWhere('auction_items.product_status', '=', 'unfulfilled')
                      ->orWhere('auction_items.product_status', '=', 'post');
            })
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
                'auction_items.product_status',
                'bidding.bid_amt'
                
            )
            ->get();

            var_dump($items);
            
            /*
              // update bidding status to win
             $bid = DB::table('auction_items')
              ->rightJoin('bidding', 'auction_items.watch_lot_id', '=', 'bidding.lot_id')
              ->where('auction_items.status','!=','deleted')
              ->where('auction_items.product_id','=','7691070177324')
             // ->where('auction_items.product_id','=','7687788265516')
              //->where('auction_items.watch_package','==','Yes Reserve Auction')// this script was addded recently
              ->where('auction_items.reserves','<=','bidding.bid_amt')// this script was addded recently
              ->where('bidding.status','!=','deleted')
              ->select(
                  'bidding.bid_amt',
                  'bidding.id'
              )
              ->orderBy('bidding.id', 'desc')
              ->get();
              var_dump($bid);
              */

              
             /*
              $bid2 = DB::table('bidding')
              ->rightJoin('auction_items','bidding.lot_id','=','auction_items.watch_lot_id')
              ->where('auction_items.status','!=','deleted')
              //->where('auction_items.product_id','=','7691070177324')
              ->where('auction_items.product_id','=','7687788265516')
              //->where('auction_items.watch_package','==','Yes Reserve Auction')// this script was addded recently
              ->where('auction_items.reserves','<=','bidding.bid_amt')// this script was addded recently
              ->where('bidding.status','!=','deleted')
              ->select(
                  'bidding.bid_amt',
                  'bidding.id'
              )
              ->orderBy('bidding.id', 'desc')
              ->first();
              var_dump($bid2);
              */
              
              // if live, ended or completed
               // $latestBid = DB::table('bidding')
                //->select('lot_id', DB::raw('MAX(bid_amt) as bid_amt'))
               // ->where('status','!=','deleted')
                //->groupBy('lot_id');
              //$items = DB::table('auction_items')
                //->leftJoinSub($latestBid, 'bidding', function ($join) {
                //    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
                //})
                $items = AuctionItems::leftJoin('bidding', function($join) {
                    $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');;
                    //->where('auction_items.reserves', '<=', 'bidding.bid_amt');
                    //->on('bidding.bid_amt', Bidding::raw('(SELECT MAX(bid_amt) from bidding  where status != "deleted")'))->select('bid_amt');
                    //$join->on('auction_items.reserves', '<=', 'bidding.bid_amt');
                  })

                ->where('auction_items.owner_user_id','=','7445465759788')
                ->where(function ($query) {
                    $query->where('auction_items.product_status', '=', 'live')
                          ->orWhere('auction_items.product_status', '=', 'ended')
                          ->orWhere('auction_items.product_status', '=', 'unfulfilled')
                          ->orWhere('auction_items.product_status', '=', 'post');
                })
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
                    'auction_items.product_status',
                    'bidding.bid_amt',
                    'bidding.id'
                    
                )
                ->get();
                /*
                $items = DB::table('auction_items AS t1')
                ->leftJoin(DB::raw('(SELECT * FROM table2 A WHERE id = (SELECT MAX(id) FROM table2 B WHERE A.id=B.id)) AS t2'), function($join) {
                    $join->on('t1.id', '=', 't2.id');
                })->get();*/

                $items = DB::table('auction_items')
                //->leftJoin('bidding', function ($join) {
                 //   $join->on('auction_items.watch_lot_id', '=', 'bidding.lot_id');
               // })
                //->select('auction_items.*', DB::raw('MAX(bidding.id) as bid_id'))
                ->where('auction_items.owner_user_id','=','7445465759788')
                ->where(function ($query) {
                    $query->where('auction_items.product_status', '=', 'live')
                          ->orWhere('auction_items.product_status', '=', 'ended')
                          ->orWhere('auction_items.product_status', '=', 'unfulfilled')
                          ->orWhere('auction_items.product_status', '=', 'post');
                })
                ->where('auction_items.status','!=','deleted')
                //->groupBy('auction_items.id')
                ->get();
                $j = 0;
                foreach( $items as $item) {
                   
                    /*
                    $bid = DB::table('bidding')
                    ->rightJoin('auction_items','bidding.lot_id','=','auction_items.watch_lot_id')
                    ->where('auction_items.status','!=','deleted')
                    //->where('auction_items.product_id','=','7691070177324')
                    ->where('auction_items.product_id','=',$item->product_id)
                    //->where('auction_items.watch_package','==','Yes Reserve Auction')// this script was addded recently
                    //->where('auction_items.reserves','<=','bidding.bid_amt')// this script was addded recently
                    ->where('bidding.status','!=','deleted')
                    ->select(
                        'bidding.bid_amt',
                        'bidding.id'
                    )
                    ->orderBy('bidding.bid_amt', 'desc')
                    ->first();
                    */
                    $bid = DB::table('bidding')
                    //->rightJoin('auction_items','bidding.lot_id','=','auction_items.watch_lot_id')
                    ->where('bidding.status','!=','deleted')
                    //->where('auction_items.product_id','=','7691070177324')
                    ->where('bidding.lot_id','=',$item->watch_lot_id)
                    //->where('auction_items.watch_package','==','Yes Reserve Auction')// this script was addded recently
                    //->where('auction_items.reserves','<=','bidding.bid_amt')// this script was addded recently
                    //->where('bidding.status','!=','deleted')
                    ->select(
                        'bidding.bid_amt',
                        'bidding.id'
                    )
                    ->orderBy('bidding.bid_amt', 'desc')
                    ->get();
                    
                    //$item->push($bid);
                    if($bid){
                       // array_push($item, array('testme'=>'dfdfdfdf'));
                       //$items[$j]->bid_amt = $bid->bid_amt;
                       //var_dump($bid);
                    }
                    $j++;
                    

                }

                //array_push($items[0], 'dfdfdf');
               // $items = $items[0]->push((object)['name' => 'Game1', 'color' => 'red']);
               //$items = array_push($items[0], array('testme'=>'dfdfdfdf'));
               ///$items = $items[0]->map(function ($item, $key) {
                //    array('testme'=>'dfdfdfdf');
                //});
                //$items = array_push($items[0], 'dfdfdfdf');
                //$games = $items[0]->push((object)(['name' => 'Game1', 'color' => 'red']));
                //$items[0]->teste = 'tesereerer';
                //var_dump($items);


                $item = DB::table('auction_items')
                ->where('product_id', '7694440398892')
                ->select(
                    'watch_lot_id',
                    'reserves'
                )
                ->first();

                
                $bid = DB::table('bidding')
                ->where('lot_id', $item->watch_lot_id)
                ->where('bid_amt', '>=', $item->reserves)
                ->select(
                   'bid_amt',
                   'id'
                )
                ->orderBy('id', 'desc')
                ->first();

                if($bid){
                    //var_dump('you are winner');
                } else {

                    //var_dump('item is no post');
                }

              //  var_dump($item);
                //var_dump($bid .'let see if may lalabas');




             

            return view('admin.unfulfilled-auctions')->with('page',$page);
        }



}
