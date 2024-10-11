<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Bidding;
use App\Models\Bidders;

use App\Http\Controllers\EmailTemplateController;

class BiddingController extends Controller
{
        
     
    public $sendingEmail;
    public function __construct(EmailTemplateController $emaitemplate)
    {
        
        $this->sendingEmail = $emaitemplate;
    }

    public function new($id=null){
            
            $page = array(
                'menu'=>'bidding',
                'subtitle'=>'Bidding Entry',
            );
            if($id){
                $bidding = Bidding::find( $id );
                $page['bidding'] = $bidding;
                $page['subtitle'] = 'Edit Bidding Entry';
            
            }
            
            return view('admin.bidding-new')->with('page',$page);

      }
      

      public function get(){

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
		
		
      }

      public function delete($id) {
		  
        $bidder =  Bidding::find($id);
        $bidder->status = 'deleted';
         if($bidder->save()) {
               echo json_encode(["msg"=>'success']);
          } else {
                echo json_encode(["msg"=>'failed']);
         }
            
     }

      
    

      public function store(Request $request) {
            
             
            $validate = Validator::make($request->all(), [
              
                'acct_id' => 'required',
                'lot_id' => 'required',
                'bid_amt' => 'required',
               
              

            ],[
                'acct_id.required' => 'Shopify User ID is required',
                'lot_id.required' => 'Lot ID is required',
                'bid_amt.required' => 'Bidding ammount is required',
               
              
                
            ]);
            if($validate->fails()){
                return back()->withErrors($validate->errors())->withInput();
            }



            if(request()->id){
              //update
              $item = Bidding::find(request()->id);
              $message = 'Bidding  edited successfully!';
            } else {
              //create
              $item = new Bidding();
              $message = 'Bidding  added successfully!';
             
            }

              
            //check if latest input big is higher than old bid
            $bid = DB::table('bidding')
            ->where('lot_id', request()->lot_id)
            //->where('bid_amt','<',request()->bid_amt)
            ->select(
                'bid_amt',
                'id'
            )
            ->latest()
            ->first();

            //if( request()->bid_amt > $bid->bid_amt ){

                $item->acct_id = request()->acct_id;
                $item->lot_id = request()->lot_id;
                $item->bid_amt = request()->bid_amt;
                $item->submitedfrom = isset(request()->submitedfrom)?request()->submitedfrom:'';


            //}
            $bid_amt = 0;
            $hightbid  = 99;
            if($bid){
               
                $bid_amt = $bid->bid_amt;
                $hightbid = $hightbid + $bid_amt;
               
               
            }


            $auction = DB::table('auction_items')
                ->where('watch_lot_id', request()->lot_id)
                ->select(
                    'id',
                    'watch_lot_id',
                    'reserves',
                    'bidding_title',
                    'product_url',
                    'owner_user_id',
                    'bidding_date'
                )
                ->where('status', '!=', 'deleted')
                ->first();

          
          //  $hightbid =  $bid_amt + 99;
           
            if( request()->bid_amt > $bid_amt && $item->save() ){ // success


                if(request()->auction_stat == 'post' ){
                        //sending email
                        //send email using template 5
                        $bidder_id =  Bidders::where('acct_id',$auction->owner_user_id)->first()->id;
                        $bidder = Bidders::find($bidder_id);
                        $response['shopify_product_url'] = 'https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url; 
                        
                        $originalDate = $auction->bidding_date;
                        // Unix time = 1685491200
                        $unixTime = strtotime($originalDate);
                        // Pass the new date format as a string and the original date in Unix time
                        $newDate = date("F j, Y", $unixTime);
                        // echo $newDate;

                        $email = array(
                            'email_code'=>'5',
                            'seller_name'=>$bidder->name,
                            'seller_email'=>$bidder->email,
                            'lot_highest_bid_price'=>request()->bid_amt,
                            'lot_number'=>request()->lot_id,
                            'lot_name'=>$auction->bidding_title,
                            'lot_sold_price'=>$auction->reserves,
                            'lot_ending_time'=>$newDate,
                            'here'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">here</a>',
                            
                        );
                        $this->sendingEmail->html_bidder_email($email);
                        $this->sendingEmail->html_admin_email($email);

                }


                if(request()->auction_stat == 'live' ){
                    //sending email
                    //send email using template 11
                    $bidder_id =  Bidders::where('acct_id',request()->acct_id)->first()->id;
                    $bidder = Bidders::find($bidder_id);

                    $response['shopify_product_url'] = 'https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url; 
                    
                    $originalDate = $auction->bidding_date;
                    // Unix time = 1685491200
                    $unixTime = strtotime($originalDate);
                    // Pass the new date format as a string and the original date in Unix time
                    $newDate = date("F j, Y", $unixTime);
                    // echo $newDate;

                    $email = array(
                        'email_code'=>'11',
                        'seller_name'=>$bidder->name,
                        'buyer_name'=>$bidder->name,
                        'seller_email'=>$bidder->email,
                        'lot_highest_bid_price'=>request()->bid_amt,
                        'lot_number'=>request()->lot_id,
                        'lot_name'=>$auction->bidding_title,
                        'lot_sold_price'=>$auction->reserves,
                        'lot_ending_time'=>$newDate,
                        'auction_end_time'=>$newDate,
                        'bid_place_time'=>date("F j, Y H:i:s"),
                        'here'=>'<a style="text-decoration:underline;" href="'.$response['shopify_product_url'].'" target="_blank">here</a>',
                        
                    );
                    $this->sendingEmail->html_bidder_email($email);
                    $this->sendingEmail->html_admin_email($email);

            }
                       






                if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                    return redirect('/bidding')->with('added', $message);
                } else {
                    //submitted from shopify
                    echo json_encode(["status"=>'success','msg'=>$message,'biddata'=>$bid]);
                    //return response()->json( array("status"=>'success','msg'=>$message) );
                }

            } else { // failed
               
                
                if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                    return redirect('/bidding')->with('failed', 'New item failed to add!');
                } else {
                    //submitted from shopify
                    echo json_encode(["status"=>'failed','msg'=>'Please bid $'.$hightbid.' or aboved','biddata'=>$bid]);
                    //return response()->json( array("status"=>'failed','msg'=>'Bid amount is lower than current bid') );
                }

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
