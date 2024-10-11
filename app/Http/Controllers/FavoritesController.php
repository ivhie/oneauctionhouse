<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



use App\Models\Favorites;

use App\Http\Controllers\EmailTemplateController;

class FavoritesController extends Controller
{
        
     
    public $sendingEmail;
    public function __construct(EmailTemplateController $emaitemplate)
    {
        
        $this->sendingEmail = $emaitemplate;
    }

    public function new($id=null){
            
           

      }
      

      public function get($liker_id){

        $favorites = DB::table('favorites')
        ->leftJoin('auction_items', 'auction_items.id', '=', 'favorites.auction_id')
        ->where('favorites.liker_id','=',$liker_id)
        ->where('favorites.like','=','1')
        ->where('auction_items.product_status','=','live')
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
            'auction_items.product_status'
        )
        ->get();
         
        $bidding_item = array();

         //query likes // pass this like count
         $likes = array();
         if(isset($favorites)){ // if bidding item has entry
         foreach($favorites as $favorite) {
 
             $likes[$favorite->auction_id] = DB::table('favorites')
             ->where('auction_id','=', $favorite->auction_id)
             ->where('like','=','1')
             ->get()
             ->count();
              
              // get hightest bidding data
             $bidding_item[$favorite->watch_lot_id] = DB::table('bidding')
                    ->where('lot_id', $favorite->watch_lot_id)
                    ->select(
                        'bid_amt',
                        'acct_id',
                        'id',
                    )
                    ->latest()
                    ->first();

 
             }
         }

         

        
        $data = [
           // "status"=>$status,
            //'msg'=>$status,
           // "favorites"=>$favorites,
           "items"=>$favorites,
           // "count"=>$items2,
            "likes"=>$likes,
            "bidding_items"=>$bidding_item,
            //"fulfillment"=>$fulfillment_data,
        ];
        return response()->json($data);
		
		
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

     public function saveFavorites($auction_id, $liker_id) {
        
        $favorites = DB::table('favorites')
        ->where('auction_id','=', $auction_id)
        ->where('liker_id','=',$liker_id)
        //->where('like','=','1')
        ->select('*')
        ->first();

        if(isset($favorites->like) && $favorites->like == '1'){
            $liker =  Favorites::find($favorites->id);
            $liker->like = '0';
            $liker->save();

        } else if( isset($favorites->like) && $favorites->like == '0') {
            $liker =  Favorites::find($favorites->id);
            $liker->like = '1';
            $liker->save();

        } else {
            
            $liker = new Favorites();
            $liker->like = '1';
            $liker->auction_id = $auction_id;
            $liker->liker_id = $liker_id;
            $liker->status = 'active';
            $liker->save();

        }

        $favoritecount = DB::table('favorites')
        ->where('auction_id','=', $auction_id)
        ->where('like','=','1')
        ->get()
        ->count();
        return response()->json( array("count"=>$favoritecount) );



     }



      public function store(Request $request) {
            
             
    
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
