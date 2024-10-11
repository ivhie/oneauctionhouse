<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AuctionFormShopifySubmission;

class AuctionFormSubmitShopifyController extends Controller
{
    
    
    public function store() {
            
             
        //$AuctionItems = new AuctionItems();
        //$last = Item::latest()->first();
        //var_dump($last);

        /*
        $validate = Validator::make($request->all(), [
            'watch_brand' => 'required',
            'watch_model_number' => 'required',
            'year_of_watch' => 'required',
            'watch_package' => 'required',
            'watch_papers' => 'required',
            'watch_box' => 'required',
            'watch_after_market' => 'required',
            'watch_condition' => 'required',
            'bidding_date' => 'required',
            'bidding_time' => 'required',
            'bidding_title' => 'required|min:10',

            

      
            
        ],[
            'watch_brand.required' => 'Watch Brand is required',
            'watch_model_number.required' => 'Watch model number is required',
            'year_of_watch.required' => 'Watch year is required',
            'watch_package.required' => 'Package is required',
            'watch_papers.required' => 'Watch papers is required',
            'watch_box.required' => 'Watch box is required',
            'watch_after_market.required' => 'After Markets Components is required',
            'watch_condition.required' => 'Watch condition is required',
            'bidding_date.required' => 'Bidding date is required',
            'bidding_time.required' => 'Bidding time is required',
            'bidding_title.required' => 'Bidding Title is required!',
            
        ]);
        if($validate->fails()){
            return back()->withErrors($validate->errors())->withInput();
        }
        */



        //if(request()->id){
        //  //update
          //$item = AuctionItems::find($id);
       // } else {
          //create
          $item = new AuctionFormShopifySubmission();
        //}

        $item->watch_lot_id = '001';
        $item->watch_brand = 'Omeage';
        $item->watch_model_number = '123455';
        $item->year_of_watch = '2024';
        $item->watch_package = 'package';
        $item->watch_papers = 'yes';
        $item->watch_box = 'yes';
        $item->watch_after_market = 'test';
        $item->watch_condition = 'condtion';
        $item->bidding_date = 'testdat';
        $item->bidding_time = 'teimdate';
        $item->bidding_title = 'test';
        $item->bidding_description = 'test';
        //$item->watch_photos = request()->watch_photos;
       // $item->watch_photos_notable_damage = request()->watch_photos_notable_damage;
        //$item->watch_photos_accessories = request()->watch_photos_accessories;
        //$item->watch_photos_id_drivers_license = request()->watch_photos_id_drivers_license;
        $item->posted_by = 'ivan';
        $item->fullfilled_status = 'unverified';
        $item->product_status = 'darft';
        $item->status = 'active';
        $item->owner_user_id = '11';
        $item->owner_user_email = 'ivandolera24@gmai.com';
        $item->auction_notes = 'nothing';
        $item->submitedfrom = 'shopify';
        //$item->save()

        if( $item->save() ){ // success

            //if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                //return redirect('/auctions')->with('added', 'New item added successfully!');
            //} else {
                //submitted from shopify
                echo json_encode(["status"=>'success','msg'=>'Succesfully added']);
            //}

        } else { // failed
           
            
            //if(request()->submitedfrom == 'laravel'){ //submitted from laravel
            //    return redirect('/auctions')->with('failed', 'New item failed to add!');
           // } else {
                //submitted from shopify
                echo json_encode(["status"=>'failed','msg'=>'Failed to list']);
            //}


        }

      


    }
}
