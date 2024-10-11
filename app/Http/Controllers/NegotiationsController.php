<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Bidding;
use App\Models\Bidders;
use App\Models\Negotiations;

use App\Models\AuctionItems;

use App\Models\Fulfillment;
use App\Services\ShopifyService;

use App\Http\Controllers\EmailTemplateController;

class NegotiationsController extends Controller
{
        
    protected $shopifyService;
    public $sendingEmail;
    public function __construct(EmailTemplateController $emaitemplate, ShopifyService $shopifyService)
    {
        $this->shopifyService = $shopifyService;
        $this->sendingEmail = $emaitemplate;
    }

    public function new($id=null){
            
            /*$page = array(
                'menu'=>'bidding',
                'subtitle'=>'Bidding Entry',
            );
            if($id){
                $bidding = Bidding::find( $id );
                $page['bidding'] = $bidding;
                $page['subtitle'] = 'Edit Bidding Entry';
            
            }
            
            return view('admin.bidding-new')->with('page',$page);
            */

      }
      

      public function get(){
          /*
        $search_this = $_GET['search']['value'];
        $start = $_GET['start'];
        $length = $_GET['length'];
        $orderby = $_GET['order'][0]['column'];
        $orderdir = $_GET['order'][0]['dir'];
        
		$bidding = Bidding::select('*');
		$bidding->where('status','!=','deleted');
		//$users->where('branch_id','=',$branch_id);
		$bidding->orderBy('created_at', 'desc');
		
        $bidding2 = Bidding::select(array('id'));
		//$users2->where('branch_id','=',$branch_id);
		$bidding2->where('status','!=','deleted');
      
        if ($search_this) {
					
					//$users->where('watch_lot_id','like', '%'.strtolower($search_this).'%');
                    $bidding->where('lot_id','=',$search_this);

		}

        $bidding_count = $records_filtered = $bidding->get()->count();
        $records_filtered = $bidding->count();
        if ($search_this) {
            $records_filtered = $bidding->count();
        }

        if($orderby == 1)
            $orderby = 'name';
        else
            $orderby = 'id';
        
		$bidding = $bidding
            ->orderBy($orderby, $orderdir)
            ->skip( $start )->take($length)
            ->get();
        
        $data = array();

		if ($bidding)
		{
		      
              foreach($bidding as $k=>$bid) {
               
				 $btn = '<!--<a class="btn  btn-primary btn-md btn-edit"  href="'.url('bidding/view/'.$bid->id).'"><i class="fa fa-pencil" aria-hidden="true"></i>View</a>&nbsp;--><a class="btn  btn-success btn-md btn-edit"  href="'.url('bidding/edit/'.$bid->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;<a class="btn  btn-danger btn-md btn-delete"  data-id="'.$bid->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
				
				
                array_push($data,array(
						$bid->id,
						$bid->acct_id,
						'Lot#'. $bid->lot_id,
                        $bid->bid_amt,
                        date('m/d/Y h:i A', strtotime($bid->created_at)),
                        isset($bid->bid_status)?$bid->bid_status:'-',
						$btn,
						
				));
              }
			  
              
		}
		echo json_encode(array('draw'=>$_GET['draw'],'recordsTotal'=>$bidding_count,'recordsFiltered'=>$records_filtered,'data'=>$data));
		*/
		
      }

      public function delete($id) {
		/*
        $bidder =  Bidding::find($id);
        $bidder->status = 'deleted';
         if($bidder->save()) {
               echo json_encode(["msg"=>'success']);
          } else {
                echo json_encode(["msg"=>'failed']);
         }
        */
            
     }

      
      
      public function getBuyerNegotiation($buyer_id){

        /*
        $bidding = DB::table('bidding')
        ->where('lot_id', $item->watch_lot_id)
        ->select(
            'bid_amt',
            'acct_id',
            'id',
        )
        ->latest()
        ->first();
        */

       

           
            $items = DB::table('auction_items')
           
            ->leftJoin('bidding', 'bidding.lot_id', '=', 'auction_items.watch_lot_id')
            ->leftJoin('negotiations', 'negotiations.auction_id', '=', 'auction_items.id')
            //->distinct('negotiations.neg_amt')
            ->where('bidding.acct_id','=',$buyer_id)
            ->where('auction_items.product_status','=', 'negotiations')
            ->where('auction_items.status','!=','deleted')
            ->where('negotiations.neg_status','=','propose')
            //->distinct('negotiations.neg_amt')
           /* ->distinct(
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
                'bidding.bid_amt',
                'negotiations.neg_amt',
                //'fulfillment.step2_fedx_tracking_number',
                //'fulfillment.step2_fedx_tracking_status',
               // 'fulfillment.steps',
                //'fulfillment.id as fulfillment_id',
               // 'fulfillment.step4_upload_authenticated_file',
               // 'fulfillment.step5_buyer_payment',
               // 'fulfillment.step6_payment_date',
                
                
            )*/
            ->distinct()
            ->get('negotiations.auction_id');


            //get negotiation Auction ID
            $neg_auction_id = DB::table('auction_items')
                        ->leftJoin('bidding', 'bidding.lot_id', '=', 'auction_items.watch_lot_id')
                        ->leftJoin('negotiations', 'negotiations.auction_id', '=', 'auction_items.id')
                        ->where('bidding.acct_id','=',$buyer_id)
                        ->where('auction_items.product_status','=', 'negotiations')
                        ->where('auction_items.status','!=','deleted')
                        ->where('negotiations.neg_status','=','propose')
                        ->distinct()
                        ->get('negotiations.auction_id');

            //get negotiation Data
            $negotiation = DB::table('auction_items')
            ->leftJoin('bidding', 'bidding.lot_id', '=', 'auction_items.watch_lot_id')
            ->leftJoin('negotiations', 'negotiations.auction_id', '=', 'auction_items.id')
            ->where('bidding.acct_id','=',$buyer_id)
            ->where('auction_items.product_status','=', 'negotiations')
            ->where('auction_items.status','!=','deleted')
            ->where('negotiations.neg_status','=','propose')
            ->distinct()
            ->get(['negotiations.id', 'negotiations.auction_id', 'negotiations.bidding_id']);

           
             // get negotiations data and auction data
             $negotiations = array();
             $auctions = array();
             $bidding = array();
             $likes = array();

            if( count($negotiation) ) { // if bidding item has entry
                
                foreach( $negotiation as $neg ) {

                        // get negotiations
                        $negotiations[$neg->auction_id] = DB::table('negotiations')
                        ->where('id', $neg->id)
                        ->select('*')
                        ->first();
                        
                        // get auctions
                        $auctions[$neg->auction_id] = DB::table('auction_items')
                        ->where('id', $neg->auction_id)
                        ->select('*')
                        ->first();
                       
                         // get biddings
                        $bidding[$neg->auction_id] = DB::table('bidding')
                        ->where('id', $neg->bidding_id)
                        ->select('*')
                        ->first();

                        // get likes
                        $likes[$neg->auction_id] = DB::table('favorites')
                        ->where('auction_id','=', $neg->auction_id)
                        ->where('like','=','1')
                        ->get()
                        ->count();
                }
                
            }
          






           
            /*
            $items = AuctionItems::query()->leftJoin('bidding', 'auction_items.watch_lot_id', '=', 'bidding.lot_id')
            ->select('auction_items.id')->distinct()
            //->selectRaw('max(bidding.id) as maxid,  auction_items.bidding_title')
            ->selectRaw(
                'max(bidding.id) as maxid,
                auction_items.product_url as product_url,
                auction_items.watch_lot_id,
                auction_items.id,
                auction_items.id as auction_id,
                auction_items.product_id,
                auction_items.bidding_title,
                auction_items.watch_brand,
                auction_items.reserves,
                auction_items.bidding_date,
                auction_items.bidding_time,
                auction_items.watch_photos,
                auction_items.owner_user_id,
                auction_items.product_status,
                bidding.bid_amt'
            )
            ->where('bidding.acct_id','=',$buyer_id)
            ->where('auction_items.product_status','=', 'negotiations')

            ->groupby('auction_items.id')
            ->groupby('auction_items.product_url')
            ->groupby('auction_items.watch_lot_id')
            ->groupby('auction_items.product_id')
            ->groupby('auction_items.bidding_title')
            ->groupby('auction_items.watch_brand')
            ->groupby('auction_items.reserves')
            ->groupby('auction_items.bidding_date')
            ->groupby('auction_items.bidding_time')
            ->groupby('auction_items.watch_photos')
            ->groupby('auction_items.owner_user_id')
            ->groupby('auction_items.product_status')
            ->groupby('bidding.bid_amt')


            ->orderby('auction_items.bidding_title')
            ->get();
            */
        
            /*
            // get hightest bidding data
            if(count($items)){ // if bidding item has entry
                
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
            if(count($items)){ // if negotionation item has entry
                
                foreach( $items as $item ) {
                        $negotiations[$item->auction_id] = DB::table('negotiations')
                        ->where('auction_id', $item->auction_id)
                        ->select('*')
                        ->latest()
                        ->first();
                }
                
            }
            */

            $data = [
                 'bidding_items'=>$bidding,
                 'items'=>$auctions,
                 'likes'=>$likes,
                 'negotiations'=>$negotiations,
                 
            ];
            return response()->json($data);




      }




      public function negotation(Request $request) {
            
          

             //get auction item details
             $auction = DB::table('auction_items')
             ->select(
                 'id',
                 'watch_lot_id',
                 'product_id',
                 'reserves',
                 'bidding_title',
                 'owner_user_id',
                 'bidding_date'
             )
             ->where('id','=',request()->auction_id)
             ->where('status', '!=', 'deleted')
             ->first();

            $neg_status =  request()->neg_status;
          
        
            if($neg_status == 'accept') { // move auction item to unfulfilled
                    
                    //set bidding item to win
                    $bidding = Bidding::find(request()->bidding_id);
                    $bidding->bid_status = 'win';
                    $bidding->bid_amt = request()->current_propose_amt; // set bid amount to current propose amount
                    $bidding->save();

                    //set auction item to unfullfilled
                    DB::table('auction_items')
                            ->where('id', request()->auction_id)
                            ->update(['product_status' => 'unfulfilled']);

                   //update product price on shopify base on propose price
                   $productData = [
                  
                        'variants' => [
                            [
                                'price' => number_format(request()->current_propose_amt, 2, '.', ''),
                                'sku' => $auction->watch_lot_id,
                                'track_quantity' => true,
                                'quantity' => 5, // Set the initial quantity if needed
                            ]
                        ]
                  
                    ];
                    //save/update on shopify
                    $this->shopifyService->updateProduct($auction->product_id,$productData);


                    //update shopify meta fields to unfulfilled
                    $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_status');
                    $response = $this->shopifyService->updateMeta(
                        $auction->product_id,
                        $bidding_status_meta_id,
                        'bidding_status',
                        'unfulfilled',
                        'single_line_text_field'
                    );





                    /* Update fullfillment table if existing */
                    
                    if(!DB::table('fulfillment')
                    ->where('product_id', $auction->product_id)
                    // ->where('auction_id', $acct_id)
                    //->where('acct_id', $acct_id)
                    ->update([
                        'step1_shipping' => 'No',
                        'steps' => '1',
                        'lot_id' => $auction->watch_lot_id,
                        //'product_id' => 'unfulfilled'
                        'auction_id' => $auction->id,
                        'buyer_id' => $bidding->acct_id,
                        'acct_id' => $auction->owner_user_id,
                        'fulfillment_status' => 'active',
                        'status' => 'active'
                        ])){
                            //insert data
                            $fulfillment = new Fulfillment();
                            $fulfillment->step1_shipping = 'No';
                            $fulfillment->steps = 1;
                            $fulfillment->lot_id = $auction->watch_lot_id;
                            $fulfillment->product_id = $auction->product_id;
                            $fulfillment->auction_id = $auction->id;
                            $fulfillment->buyer_id = $bidding->acct_id; //get bidder ID
                            $fulfillment->acct_id = $auction->owner_user_id;
                            $fulfillment->fulfillment_status = 'active';
                            $fulfillment->status = 'active';
                            $fulfillment->save();

                    }


                    $data = [
                        'msg'=>'accept',
                    
                    ];
                    return response()->json($data);

                    


            } else if($neg_status == 'decline'){

                    // move auction item to post
                  if( DB::table('auction_items')
                   ->where('id', request()->auction_id)
                   ->update(['product_status' => 'post'])) {
                        
                        //update shopify biddig status
                        
                        $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_status');
                        $response = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $bidding_status_meta_id,
                            'bidding_status',
                            'post',
                            'single_line_text_field'
                        );
                
                        $data = [
                            'msg'=>'decline',
                        
                        ];
                        return response()->json($data);
                        
                        
                   }

//
            }  else if($neg_status == 'propose') {
                   // move to negotiations
                    //get bidding item details
                    $bidding = DB::table('bidding')
                    ->select(
                        'bid_amt'
                    )
                    ->where('id','=',request()->bidding_id)
                    ->where('status', '!=', 'deleted')
                    ->first();

                    if(request()->offer_amount <= $bidding->bid_amt) {
                        $data = [
                            'msg'=>'bidding-below',
                        ];
                        return response()->json($data);
                
                    } else {

                        $item = new Negotiations();
                        $item->auction_id = request()->auction_id;
                        $item->buyer_id = request()->buyer_id;
                        $item->seller_id = request()->seller_id;
                        $item->bidding_id = request()->bidding_id;
                        $item->propose_by = request()->propose_by;
                        $item->neg_confirm = 'pending';
                        $item->neg_amt = isset(request()->offer_amount)?request()->offer_amount:'0';
                        $item->neg_description = isset(request()->neg_description)?request()->neg_description:'';
                        $item->neg_status = request()->neg_status;
                        $item->status = 'active';
                        $item->save();
                        
                    
                    
                        $data = [
                            'msg'=>'propose',
                        
                        ];
                        return response()->json($data);


                    }

                    
        

           } else if($neg_status == ''){
                
                    $data = [
                        'msg'=>'non-selected',
                    ];
                    return response()->json($data);

           }

            
          
      }
      
   


    public function index(){
       
        

        $page = array(
            'menu'=>'bidding',
        );
        //var_dump($products);
        return view('admin.bidding')->with('page',$page);

    }

   
}
