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



class ActiveAuctionsController extends Controller
{
    protected $shopifyService;
    public $lot;
    public $bid;
    public $b;
 
     public function __construct(ShopifyService $shopifyService, AuctionItems $lot, Bidders $bidders,Bidders $bidder  )
     {
         $this->shopifyService = $shopifyService;
         $this->lot = $lot;
         $this->bid = $bidders;
         $this->b = $bidder;
     }
         
         public $client;
         
        
 
         public function UpdatePublishedAuction($id){
             
           
             $auction = AuctionItems::find($id);
             $productData = [
                 'title' => $auction->bidding_title,
                 'body_html' => $auction->bidding_description,
                 'vendor' => $auction->watch_brand,
                     
                 "template_suffix" => "watch-bidding", 
                 'status' => 'active',
                 'productType' => 'Watch',
                 'variants' => [
                     [
                         //"option1" => "Default Title",
                         'price' => number_format($auction->reserves, 2, '.', ''),
                         'sku' => $auction->watch_lot_id,
                         'track_quantity' => true,
                         'quantity' => 5, // Set the initial quantity if needed
                     ]
                ],
                 /*"images" => [
                        [
                            "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/12_d05706fd-8c18-4527-8ba7-2366463b7062.jpg?"  // Default image URL
                       ] ,
                       [
                         "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/20240522-172309.jpg"  // Default image URL
                        ] 
                    ],*/
 
                
 
             ];
             
             // query the images
             $lotid = $auction->watch_lot_id;
             $watch_photos = $auction->watch_photos;
             if($watch_photos){

                    $watch_photos = explode(",",$watch_photos);

                    if(count($watch_photos)){
                        $productData ['images'] = array();
                        foreach( $watch_photos as $watch_photo ){

                            //$fileUrl = public_path('watches/lot-'.$lotid.'/'.$watch_photo);
                            $fileUrl = URL::asset('public/watches/lot-'.$auction->watch_lot_id.'/'.$watch_photo);
                           // echo  $fileUrl;
                            $productData['images'][] = array(
                                'src' => url($fileUrl),
                                'title'=>'Lot #'.$lotid
                            );
                                
    
                        }
                    }
                    
             }
             
     
             try {
 
                 
                 $response[]= $this->shopifyService->updateProduct($auction->product_id,$productData);
 
                 
                  //$response[] = $this->shopifyService->getAllProductMeta($auction->product_id);
                  $watch_brand_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_brand');
                  $response[] = $this->shopifyService->updateMeta(
                     $auction->product_id,
                     $watch_brand_meta_id,
                     'watch_brand',
                     $auction->watch_brand,
                     'single_line_text_field'
                 );
 
                 $watch_model_number_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_model_number');
                 $response[] = $this->shopifyService->updateMeta(
                    $auction->product_id,
                    $watch_model_number_meta_id,
                    'watch_model_number',
                    $auction->watch_model_number,
                    'single_line_text_field'
                );
 
               $year_of_watch_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'year_of_watch');
                $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $year_of_watch_meta_id,
                   'year_of_watch',
                   $auction->year_of_watch,
                   'single_line_text_field'
               );
               
               
               $watch_papers_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_papers');
                $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $watch_papers_meta_id,
                   'watch_papers',
                   $auction->watch_papers,
                   'single_line_text_field'
               );
               
                $watch_box_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_box');
                $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $watch_box_meta_id,
                   'watch_box',
                   $auction->watch_box,
                   'single_line_text_field'
               );
              
               $watch_non_parts_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_non_parts');
                $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $watch_non_parts_meta_id,
                   'watch_non_parts',
                   $auction->watch_after_market,
                   'single_line_text_field'
               );
              
               $watch_condition_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_condition');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $watch_condition_meta_id,
                   'watch_condition',
                   $auction->watch_condition,
                   'single_line_text_field'
               );
               
               $watch_package_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_package');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $watch_package_meta_id,
                   'watch_package',
                   $auction->watch_package,
                   'single_line_text_field'
               );
 
               
               $watch_reserve_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_reserve');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $watch_reserve_meta_id,
                   'watch_reserve',
                   isset($auction->reserves)?$auction->reserves:0,
                   'single_line_text_field'
               );
             
               $bidding_title_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_title');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $bidding_title_meta_id,
                   'bidding_title',
                   $auction->bidding_title,
                   'single_line_text_field'
               );
               
               /*
               $bidding_description_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_description');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $bidding_description_meta_id,
                   'bidding_description',
                   $auction->bidding_description,
                   'multi_line_text_field'
               );*/
               
               
 
               $bidding_date_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_date');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $bidding_date_meta_id,
                   'bidding_date',
                   $auction->bidding_date,
                   'single_line_text_field'
               );
 
               
               $bidding_time_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_time');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $bidding_time_meta_id,
                   'bidding_time',
                   $auction->bidding_time,
                   'single_line_text_field'
               );
               
               $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_status');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $bidding_status_meta_id,
                   'bidding_status',
                   $auction->product_status,
                   'single_line_text_field'
               );
  
               
               $owner_id_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'owner_id');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $owner_id_meta_id,
                   'owner_id',
                   $auction->owner_user_id,
                   'single_line_text_field'
               );
               

               $item_location_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'item_location');
               $response[] = $this->shopifyService->updateMeta(
                   $auction->product_id,
                   $item_location_meta_id,
                   'item_location',
                   $auction->item_location,
                   'single_line_text_field'
               );

 
               //$response['shopify_product_admin_url'] = 
               //$response['shopify_product_admin_url'] = 
              // $response['shopify_product_url'] = '<a  style="margin-top:6px;" href="//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url.'" target="_blank" class="btn btn-warning">View Shopify</a>'; 
               //$response['shopify_product_admin_url'] = '<a  style="margin-top:6px;" href="//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id.'" target="_blank" class="btn btn-warning">View Product Admin</a>'; 
                 
                 //$response = $client->request('GET', "products/{$productId}/metafields.json");  
                 return response()->json($response);
             } catch (\Exception $e) {
                 return response()->json(['error' => $e->getMessage()], 500);
             }
             
         }
 
 
         public function publishedAuction(Request $request) : JsonResponse  {


             //validate here
             $this->validate($request, [
                'product_status' => 'required',
                'watch_brand' => 'required',
                'owner_user_id' => 'required',
                'watch_model_number' => 'required',
                'year_of_watch' => 'required',
                'watch_package' => 'required',
                'watch_papers' => 'required',
                'watch_box' => 'required',
                'watch_after_market' => 'required',
                'watch_condition' => 'required',
                //'watch_reserve' => 'required',
                //'uploadphotos.*' => 'image|mimes:png,jpg,jpeg,webp|max:510240',//max
                //'watch_photos_notable_damage.*' => 'image|mimes:png,jpg,jpeg,webp|max:510240',//max
                'bidding_date' => 'required',
                'bidding_time' => 'required',
                'bidding_title' => 'required',
                'item_location' => 'required',

            ],[
                'product_status .required' => 'Auction status is required!',
                'watch_brand.required' => 'Watch Brand is required!',
                'owner_user_id.required' => 'Seller name is required!',
                'watch_model_number.required' => 'Watch model number is required!',
                'year_of_watch.required' => 'Watch year is required!',
                'watch_package.required' => 'Package is required!',
                'watch_papers.required' => 'Watch papers is required!',
                'watch_box.required' => 'Watch box is required!',
                'watch_after_market.required' => 'After Markets Components is required!',
                'watch_condition.required' => 'Watch condition is required!',
                //'watch_reserve.required' => 'Reserve is required',
                //'uploadphotos.required' => 'Upload photos is required',
                //'uploadphotos.image' => 'Upload photos  only',
                //'uploadphotos.mimes' => 'Photos png,jpg,jpeg,webp is only accepted',
                //'uploadphotos.max' => 'Photos max size is 50mb',
                //'watch_photos_notable_damage.image' => 'Upload photos  only',
                //'watch_photos_notable_damage.mimes' => 'Photos png,jpg,jpeg,webp is only accepted',
                // 'watch_photos_notable_damage.max' => 'Photos max size is 50mb',
                //'watch_condition.required' => 'Watch condition is required',
                'bidding_date.required' => 'Bidding date is required!',
                'bidding_time.required' => 'Bidding time is required!',
                'bidding_title.required' => 'Bidding Title is required!',
                'item_location.required' => 'Item Location is required!',
                
            ]);

           
            //$auction = AuctionItems::find(request()->id);

            if(isset(request()->id)){
                //update
                $auction = AuctionItems::find(request()->id);
                $lotid = request()->watch_lot_id;
                $sucess_msg = 'Lot #'.$lotid.' updated successfully!';
                
              } else {
                //create
                $auction = new AuctionItems();
                $lotid = $this->lot->LotNumber();
                $auction->watch_lot_id = $lotid;
                $sucess_msg = 'New Lot #'.$lotid.' added successfully!';
                
              }
                
            
              // Process the form data
              //$watch_photos_data = [];
              
              if($request->hasFile('watch_photos')) {
                  $n= 0;
                  foreach ($request->file('watch_photos') as $file) {
                      $extension = $file->getClientOriginalExtension();
                     // $filename = $file->getClientOriginalName().'-'.time(). '.' .$extension;
                      $filename = $n.'-'.time(). '.' .$extension;
                      //$path = $file->store('watches/lot-'.$this->lot->LotNumber(),'public');
                      //$path = $file->store('images', 'public');
                      $destinationPath = public_path('watches/lot-'.$lotid);
                      $file->move($destinationPath, $filename);
                      $watch_photos_data[] = $filename;
                      $n++;
                  }
              }
  
             
              //watch photos
              if(isset($watch_photos_data) && !empty($watch_photos_data)) {
                  $watch_photos_data = (implode(",", $watch_photos_data));
              } else {
                  $watch_photos_data = '';
              }
  
             
              $auction->watch_brand = request()->watch_brand;
              $auction->watch_model_number = request()->watch_model_number;
              $auction->year_of_watch = request()->year_of_watch;
              $auction->watch_package = request()->watch_package;
              $auction->watch_papers = request()->watch_papers;
              $auction->watch_box = request()->watch_box;
              $auction->watch_after_market = request()->watch_after_market;
              $auction->watch_condition = request()->watch_condition;
              $auction->reserves = request()->watch_reserve;
              $auction->uploadphotos = isset(request()->uploadphotos)?request()->uploadphotos:'';
              $auction->bidding_date = isset(request()->bidding_date)?request()->bidding_date:'';
              $auction->bidding_time = isset(request()->bidding_time)?request()->bidding_time:'';
              $auction->bidding_title = isset(request()->bidding_title)?request()->bidding_title:'';
              $auction->item_location = isset(request()->item_location)?request()->item_location:'';
              $auction->bidding_description = isset(request()->bidding_description)?request()->bidding_description:'';
              if($watch_photos_data){
                  $auction->watch_photos = $watch_photos_data;
              }
  
              $auction->posted_by = isset(request()->posted_by)?request()->posted_by:'';
              $auction->fullfilled_status = isset(request()->fullfilled_status)?request()->fullfilled_status:'';
              $auction->product_status = isset(request()->product_status)?request()->product_status:'pending';
              $auction->status = 'active';
              $auction->owner_user_id = isset(request()->owner_user_id)?request()->owner_user_id:'';
              $auction->owner_user_email = isset(request()->owner_user_email)?request()->owner_user_email:'';
              $auction->auction_notes = isset(request()->auction_notes)?request()->auction_notes:'';
              $auction->submitedfrom = isset(request()->submitedfrom)?request()->submitedfrom:'';
         
                
             /*
            if( $auction->save() ){ // success
                     // $result = ["status"=>'success','msg'=>$sucess_msg,"lotId"=>$lotid];
                      //echo json_encode( array('result'=>'success','message'=>'Success') ); die();
                      $status = 'success';
                     
                      
              } else { // failed
                      //submitted from shopify
                      //$result = ["status"=>'failed','msg'=>'Failed to list',"lotId"=>$lotid];
                    //  echo json_encode( array('result'=>'success','message'=>'Success') ); die();
                    $status = 'failed';
                    $sucess_msg = 'Failed to list';

              }

              echo json_encode( array('status'=>$status,'msg'=>$sucess_msg,"lotId"=>$lotid,"upload_image_urls"=>$watch_photos_data) ); die();
              //return response()->json($result);
             */



             

            
             //$auction = AuctionItems::find(request()->id);


            if($auction->product_url) { // run this,if this auction is push already to shopify
                
                    $productData = [
                        'title' => $auction->bidding_title,
                        'body_html' => $auction->bidding_description,
                        'vendor' => $auction->watch_brand,
                            
                        "template_suffix" => "watch-bidding", 
                        'status' => 'active',
                        'productType' => 'Watch',
                        'variants' => [
                            [
                                //"option1" => "Default Title",
                                'price' => number_format($auction->reserves, 2, '.', ''),
                                'sku' => $auction->watch_lot_id,
                                'track_quantity' => true,
                                'quantity' => 5, // Set the initial quantity if needed
                            ]
                    ],
                        /*"images" => [
                            [
                                "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/12_d05706fd-8c18-4527-8ba7-2366463b7062.jpg?"  // Default image URL
                            ] ,
                            [
                                "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/20240522-172309.jpg"  // Default image URL
                            ] 
                        ],*/
        
                    
        
                    ];
                    
                    // query the images
                    $lotid = $auction->watch_lot_id;
                    $watch_photos = $auction->watch_photos;
                   
                    if($watch_photos && $watch_photos_data ){
    
                        $watch_photos = explode(",",$watch_photos);
    
                        if(count($watch_photos)){
                            $productData ['images'] = array();
                            foreach( $watch_photos as $watch_photo ){
    
                                //$fileUrl = public_path('watches/lot-'.$lotid.'/'.$watch_photo);
                                $fileUrl = URL::asset('public/watches/lot-'.$auction->watch_lot_id.'/'.$watch_photo);
                                // echo  $fileUrl;
                                $productData['images'][] = array(
                                    'src' => url($fileUrl),
                                    'title'=>'Lot #'.$lotid
                                );
                                    
        
                            }
                        }
                        
                    }
                    
            
                    try {
        
                        
                        $response[]= $this->shopifyService->updateProduct($auction->product_id,$productData);
        
                        
                        //$response[] = $this->shopifyService->getAllProductMeta($auction->product_id);
                        $watch_brand_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_brand');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_brand_meta_id,
                            'watch_brand',
                            $auction->watch_brand,
                            'single_line_text_field'
                        );
        
                        $watch_model_number_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_model_number');
                        $response[] = $this->shopifyService->updateMeta(
                        $auction->product_id,
                        $watch_model_number_meta_id,
                        'watch_model_number',
                        $auction->watch_model_number,
                        'single_line_text_field'
                        );
        
                        $year_of_watch_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'year_of_watch');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $year_of_watch_meta_id,
                            'year_of_watch',
                            $auction->year_of_watch,
                            'single_line_text_field'
                        );
                    
                    
                        $watch_papers_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_papers');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_papers_meta_id,
                            'watch_papers',
                            $auction->watch_papers,
                            'single_line_text_field'
                        );
                    
                        $watch_box_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_box');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_box_meta_id,
                            'watch_box',
                            $auction->watch_box,
                            'single_line_text_field'
                        );
                    
                        $watch_non_parts_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_non_parts');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_non_parts_meta_id,
                            'watch_non_parts',
                            $auction->watch_after_market,
                            'single_line_text_field'
                        );
                    
                        $watch_condition_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_condition');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_condition_meta_id,
                            'watch_condition',
                            $auction->watch_condition,
                            'single_line_text_field'
                        );
                    
                        $watch_package_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_package');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_package_meta_id,
                            'watch_package',
                            $auction->watch_package,
                            'single_line_text_field'
                        );
        
                    
                        $watch_reserve_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'watch_reserve');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $watch_reserve_meta_id,
                            'watch_reserve',
                            isset($auction->reserves)?$auction->reserves:0,
                            'single_line_text_field'
                        );
                    
                        $bidding_title_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_title');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $bidding_title_meta_id,
                            'bidding_title',
                            $auction->bidding_title,
                            'single_line_text_field'
                        );
                    
                        /*
                        $bidding_description_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_description');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $bidding_description_meta_id,
                            'bidding_description',
                            $auction->bidding_description,
                            'multi_line_text_field'
                        );*/
                    
                    
        
                        $bidding_date_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_date');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $bidding_date_meta_id,
                            'bidding_date',
                            $auction->bidding_date,
                            'single_line_text_field'
                        );
        
                    
                        $bidding_time_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_time');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $bidding_time_meta_id,
                            'bidding_time',
                            $auction->bidding_time,
                            'single_line_text_field'
                        );
                    
                        $bidding_status_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'bidding_status');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $bidding_status_meta_id,
                            'bidding_status',
                            $auction->product_status,
                            'single_line_text_field'
                        );
            
                    
                        $owner_id_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'owner_id');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $owner_id_meta_id,
                            'owner_id',
                            $auction->owner_user_id,
                            'single_line_text_field'
                        );
                    
    
                        $item_location_meta_id = $this->shopifyService->getMetaIDByKey($auction->product_id,'item_location');
                        $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $item_location_meta_id,
                            'item_location',
                            $auction->item_location,
                            'single_line_text_field'
                        );

                        $auction->save(); // save function here!
                        //save to larave 
                        $status = 'success';
                        echo json_encode( 
                            array(
                             'status'=>$status,
                             'msg'=>$sucess_msg,
                             'lotId'=>$lotid,
                             'upload_image_urls'=>$watch_photos_data,
                             'shopify_product_url'=>'//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url,
                             'shopify_product_admin_url'=>'//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id
                            ) 
                        ); die();


                    } catch (\Exception $e) {


                        $status = 'failed';
                        $sucess_msg = 'Failed to inesert either laravel database or shopify';
                        echo json_encode( 
                            array(
                             'status'=>$status,
                             'msg'=>$sucess_msg,
                             'lotId'=>$lotid,
                             'upload_image_urls'=>$watch_photos_data,
                             'shopify_product_url'=>'//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url,
                             'shopify_product_admin_url'=>'//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id
                            ) 
                        ); die();
                    }


             } else { 

                $productData = [
                    'title' => $auction->bidding_title,
                    'body_html' => $auction->bidding_description,
                    'vendor' => $auction->watch_brand,
                        
                    "template_suffix" => "watch-bidding", 
                    'status' => 'active',
                    'productType' => 'Watch',
                    'variants' => [
                        [
                            //"option1" => "Default Title",
                            'price' => number_format($auction->reserves, 2, '.', ''),
                            'sku' => $auction->watch_lot_id,
                            'track_quantity' => true,
                            'quantity' => 5, // Set the initial quantity if needed
                        ]
                    ],
                    
                    //"images" => [
                    //      [
                    //          "src" => "https://z9j.9f8.mytemp.website/public/watches/lot-096/0-1722620193.webp"  // Default image URL
                    //      ] ,
                    //       [
                    //        "src" => "https://z9j.9f8.mytemp.website/public/watches/lot-096/1-1722620193.webp"  // Default image URL
                    //   ] 
                    // ],
    
                    "metafields" => [
                        [
                            "namespace" => "custom",
                            "key" => "watch_brand",
                            "value" => $auction->watch_brand,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_model_number",
                            "value" => $auction->watch_model_number,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "year_of_watch",
                            "value" => $auction->year_of_watch,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_papers",
                            "value" => $auction->watch_papers,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_box",
                            "value" => $auction->watch_box,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_non_parts",
                            "value" => $auction->watch_after_market,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_condition",
                            "value" => $auction->watch_condition,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_package",
                            "value" => $auction->watch_package,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "watch_reserve",
                            "value" =>  isset($auction->reserves)?$auction->reserves:0,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "bidding_title",
                            "value" => $auction->bidding_title,
                            "type" => "single_line_text_field"
                        ],
                        
                        [
                            "namespace" => "custom",
                            "key" => "bidding_date",
                            "value" => $auction->bidding_date,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "bidding_time",
                            "value" => $auction->bidding_time,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "bidding_status",
                            "value" => $auction->product_status,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "owner_id",
                            "value" => $auction->owner_user_id,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "lot_number",
                            "value" => $auction->watch_lot_id,
                            "type" => "single_line_text_field"
                        ],
                        [
                            "namespace" => "custom",
                            "key" => "item_location",
                            "value" => $auction->item_location,
                            "type" => "single_line_text_field"
                        ]
    
                    ]
    
    
    
                ];


                // query the images
                $lotid = $auction->watch_lot_id;
                $watch_photos = $auction->watch_photos;
                if($watch_photos){

                        $watch_photos = explode(",",$watch_photos);

                        if(count($watch_photos)){
                            $productData ['images'] = array();
                            foreach( $watch_photos as $watch_photo ){

                                //$fileUrl = public_path('watches/lot-'.$lotid.'/'.$watch_photo);
                                $fileUrl = URL::asset('public/watches/lot-'.$auction->watch_lot_id.'/'.$watch_photo);
                            // echo  $fileUrl;
                                $productData['images'][] = array(
                                    'src' => url($fileUrl),
                                    'title'=>'Lot #'.$lotid
                                );
                                    
        
                            }
                        }
                        
                }


     
                try {
    
                        $response = $this->shopifyService->createProduct($productData);
                        //save product id
                        $auction->product_id = $response['product']['id'];
                        $auction->product_url = $response['product']['handle'];
                        $auction->save();
                        //$response['shopify_product_url'] = '//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url; 
                        //$response['shopify_product_admin_url'] = '//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id; 
                       
                        $status = 'success';
                        echo json_encode( 
                            array(
                             'status'=>$status,
                             'msg'=>$sucess_msg,
                             'lotId'=>$lotid,
                             'upload_image_urls'=>$watch_photos_data,
                             'shopify_product_url'=>'//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url,
                             'shopify_product_admin_url'=>'//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id
                            ) 
                        ); die();

                        //return response()->json($response);
                } catch (\Exception $e) {
              
                    $status = 'failed';
                    $sucess_msg = 'Failed to inesert either laravel database or shopify';
                    echo json_encode( 
                        array(
                         'status'=>$status,
                         'msg'=>$sucess_msg,
                         'lotId'=>$lotid,
                         'upload_image_urls'=>$watch_photos_data,
                         'shopify_product_url'=>'//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url,
                         'shopify_product_admin_url'=>'//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id
                        ) 
                    ); die();
                    //return response()->json(['error' => $e->getMessage()], 500);
                } 


             
            }

         




    }
 
 
    
 
 
 
 
       public function new($id=null){
             
             $page = array(
                 'menu'=>'active',
                 'page_title'=>'Active Auction Listing',
                 'subtitle'=>'Active Auction Entry',
                 'status'=>'active-acitions',
                 'bidders'=>$this->bid->getBidders(),
                 //'bidders'=>  AuctionItems::getBillders2(),
                 'bidders2'=>app('App\Http\Controllers\BiddersController')->getBillders2()
                 //'bidders'=>$this->shopifyService->getUsers(),
                 //$users = $this->shopifyService->getUsers()
             );
             //var_dump(count(app('App\Http\Controllers\BiddersController')->getBillders2()));
             if($id){
                 $auction = AuctionItems::find( $id );
                 $page['product_url'] = '<a  style="margin-top:8px;display:block;" href="//'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url.'" target="_blank" class="btn btn-warning">View Auction Shopify</a>'; 
                 $page['product_url2'] = '<a  style="margin-top:8px;display:block;" href="//admin.shopify.com/store/76c14a-0b/products/'.$auction->product_id.'" target="_blank" class="btn btn-warning">View Auction Admin</a>'; 
                 $page['auction'] = $auction;
                 $page['subtitle'] = 'Edit Auction Entry';
                 $page['bidderD'] = $this->b->getBidder($auction->owner_user_id);
                 //var_dump( $this->b->getBidder($auction->owner_user_id));
                 // set up url on this part for watch photos
                 //var_dump($page['bidderD']);
                 $urls = array();
                 if( isset($auction->watch_photos) &&  $auction->watch_photos != '') { 
 
                     $image_urls = explode(',', $auction->watch_photos);
                     foreach ( $image_urls as $url ) {
                         //$urls[] = Storage::url('app'.$url);
                         //$urls[] = url('storage/app/public/'.$url);
                         //$urls[] = Storage::disk('public')->url($url);
                         //$urls[] = url('public/assets/adminstyle/watches/lot-'.$auction->watch_lot_id.'/'.$url);
                         $urls[] = URL::asset('public/watches/lot-'.$auction->watch_lot_id.'/'.$url);
                     }
                     
 
                 }


                 $page['watch_photos_urls'] = $urls;
 
                 $page['watch_photos_notable_damage'] = $auction->watch_photos_notable_damage;
                 $page['watch_photos_accessories'] =  $auction->watch_photos_accessories;
                 $page['watch_photos_id_drivers_license'] =  $auction->watch_photos_id_drivers_license;
 
                
 
 
             }
             //$url = Storage::disk('public')->url($filePath);
             //var_dump(public_path('storage'));
             return view('admin.active-auctions-new')->with('page',$page);
 
       }
       
     
       public function download($lotid=null){
             
             //$zip_file = 'Lot-'.$lotid.'.zip';
             $zip_file = public_path('watches/shopify/lot-'.$lotid.'.zip'); // zip file location here
             $zip = new \ZipArchive();
             $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
             //$path = storage_path('invoices');
             $path = public_path('watches/shopify/lot-'.$lotid); //download all images base from lotid
             $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
 
            // $files[] = array();
             foreach ($files as $name => $file)
             {
                 // We're skipping all subfolders
                 if (!$file->isDir()) {
                     $filePath     = $file->getRealPath();
 
                     // extracting filename with substr/strlen
                     $relativePath = substr($filePath, strlen($path) + 1);
 
                     $zip->addFile($filePath, $relativePath);
                 }
                // $files[] =  $file->getRealPath();
             }
             $zip->close();
             return response()->download($zip_file);
       }
 
       public function get(){
 
         $search_this = $_GET['search']['value'];
         $start = $_GET['start'];
         $length = $_GET['length'];
         $orderby = $_GET['order'][0]['column'];
         $orderdir = $_GET['order'][0]['dir'];
         
         
         $auctions = AuctionItems::select('*');
         $auctions->where('status','!=','deleted');
         $auctions->where('product_status','=','live');
         //$users->where('branch_id','=',$branch_id);
         $auctions->orderBy('watch_lot_id', 'desc');
        
 
     
         $auctions2 = AuctionItems::select(array('id'));
         //$users2->where('branch_id','=',$branch_id);
         $auctions2->where('status','!=','deleted');
         $auctions2->where('product_status','=','live');
       
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
                     $viewlineshopify = '<a  style="margin-bottom:5px;" class="btn btn-warning" target="_blank" href="https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url.'">View Display Shopify</a>&nbsp;';
                        $viewlineshopify .= '<a style="margin-bottom:5px;" class="btn btn-warning" target="_blank" href="//'.env("SHOPIFY_STORE_BACKEND_ADMIN").'/products/'.$auction->product_id.'">View Admin Shopify</a>&nbsp;'; 
                 }
                  $btn = '<!--<a class="btn  btn-primary btn-md btn-edit"  href="'.url('pending-approvals/view/'.$auction->id).'"><i class="fa fa-pencil" aria-hidden="true"></i>View</a>&nbsp;-->'.$viewlineshopify.'<a style="margin-bottom:5px;" class="btn  btn-success btn-md btn-edit"  href="'.url('active-auctions/edit/'.$auction->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;<a style="margin-bottom:5px;" class="btn  btn-danger btn-md btn-delete"  data-id="'.$auction->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                 
                 
                 array_push($data,array(
                         $auction->id,
                         'Lot#'.$auction->watch_lot_id,
                         $auction->bidding_title,
                         '$'.$newbidamt,
                         isset($auction->product_status)?ucfirst($auction->product_status):'----',
                         date('m/d/Y', strtotime($auction->created_at)),
                         $auction->bidding_date,
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
 
       
     
 
       public function AddAuctionToLaravelDB(Request $request) {
             
             
             $validate = Validator::make($request->all(), [
                 'watch_brand' => 'required',
                 'owner_user_id' => 'required',
                 'watch_model_number' => 'required',
                 'year_of_watch' => 'required',
                 'watch_package' => 'required',
                 'watch_papers' => 'required',
                 'watch_box' => 'required',
                 'watch_after_market' => 'required',
                 'watch_condition' => 'required',
                 //'watch_reserve' => 'required',
                 'uploadphotos.*' => 'image|mimes:png,jpg,jpeg,webp|max:510240',//max
                 'watch_photos_notable_damage.*' => 'image|mimes:png,jpg,jpeg,webp|max:510240',//max
                 //'bidding_date' => 'required',
                 //'bidding_time' => 'required',
                 //'bidding_title' => 'required',
 
             ],[
                 'watch_brand.required' => 'Watch Brand is required',
                 'owner_user_id.required' => 'Seller name is required',
                 'watch_model_number.required' => 'Watch model number is required',
                 'year_of_watch.required' => 'Watch year is required',
                 'watch_package.required' => 'Package is required',
                 'watch_papers.required' => 'Watch papers is required',
                 'watch_box.required' => 'Watch box is required',
                 'watch_after_market.required' => 'After Markets Components is required',
                 'watch_condition.required' => 'Watch condition is required',
                 //'watch_reserve.required' => 'Reserve is required',
                 'uploadphotos.required' => 'Upload photos is required',
                 'uploadphotos.image' => 'Upload photos  only',
                 'uploadphotos.mimes' => 'Photos png,jpg,jpeg,webp is only accepted',
                 'uploadphotos.max' => 'Photos max size is 50mb',
                 'watch_photos_notable_damage.image' => 'Upload photos  only',
                 'watch_photos_notable_damage.mimes' => 'Photos png,jpg,jpeg,webp is only accepted',
                  'watch_photos_notable_damage.max' => 'Photos max size is 50mb',
                 //'watch_condition.required' => 'Watch condition is required',
                 //'bidding_date.required' => 'Bidding date is required',
                 //'bidding_time.required' => 'Bidding time is required',
                 //'bidding_title.required' => 'Bidding Title is required!',
                 
             ]);
             if($validate->fails()){
                 return back()->withErrors($validate->errors())->withInput();
             }
             
           
             
             if(isset(request()->id)){
               //update
               $item = AuctionItems::find(request()->id);
               $lotid = request()->watch_lot_id;
               $sucess_msg = 'Lot #'.$lotid.' updated successfully!';
               
             } else {
               //create
               $item = new AuctionItems();
               $lotid = $this->lot->LotNumber();
               $item->watch_lot_id = $lotid;
               $sucess_msg = 'New Lot #'.$lotid.' added successfully!';
               
             }
               
           
             // Process the form data
             //$watch_photos_data = [];
             
             if($request->hasFile('watch_photos')) {
                 $n= 0;
                 foreach ($request->file('watch_photos') as $file) {
                     $extension = $file->getClientOriginalExtension();
                    // $filename = $file->getClientOriginalName().'-'.time(). '.' .$extension;
                     $filename = $n.'-'.time(). '.' .$extension;
                     //$path = $file->store('watches/lot-'.$this->lot->LotNumber(),'public');
                     //$path = $file->store('images', 'public');
                     $destinationPath = public_path('watches/lot-'.$lotid);
                     $file->move($destinationPath, $filename);
                     $watch_photos_data[] = $filename;
                     $n++;
                 }
             }
 
            
             //watch photos
             if(isset($watch_photos_data) && !empty($watch_photos_data)) {
                 $watch_photos_data = (implode(",", $watch_photos_data));
             } else {
                 $watch_photos_data = '';
             }
 
            
             $item->watch_brand = request()->watch_brand;
             $item->watch_model_number = request()->watch_model_number;
             $item->year_of_watch = request()->year_of_watch;
             $item->watch_package = request()->watch_package;
             $item->watch_papers = request()->watch_papers;
             $item->watch_box = request()->watch_box;
             $item->watch_after_market = request()->watch_after_market;
             $item->watch_condition = request()->watch_condition;
             $item->reserves = request()->watch_reserve;
             $item->uploadphotos = isset(request()->uploadphotos)?request()->uploadphotos:'';
             $item->bidding_date = isset(request()->bidding_date)?request()->bidding_date:'';
             $item->bidding_time = isset(request()->bidding_time)?request()->bidding_time:'';
             $item->bidding_title = isset(request()->bidding_title)?request()->bidding_title:'';
             $item->item_location = isset(request()->item_location)?request()->item_location:'';
             $item->bidding_description = isset(request()->bidding_description)?request()->bidding_description:'';
             if($watch_photos_data){
                 $item->watch_photos = $watch_photos_data;
             }
 
 
           
             //$item->watch_photos_notable_damage = $watch_photos_notable_damage_data;
             //$item->watch_photos_accessories = request()->watch_photos_accessories;
             //$item->watch_photos_id_drivers_license = request()->watch_photos_id_drivers_license;
             $item->posted_by = isset(request()->posted_by)?request()->posted_by:'';
             $item->fullfilled_status = isset(request()->fullfilled_status)?request()->fullfilled_status:'';
             $item->product_status = isset(request()->product_status)?request()->product_status:'pending';
             $item->status = 'active';
             $item->owner_user_id = isset(request()->owner_user_id)?request()->owner_user_id:'';
             $item->owner_user_email = isset(request()->owner_user_email)?request()->owner_user_email:'';
             $item->auction_notes = isset(request()->auction_notes)?request()->auction_notes:'';
             $item->submitedfrom = isset(request()->submitedfrom)?request()->submitedfrom:'';
             //$item->save()
 
 
             /* Save Also Bidders Info if not exist */
             /*
             if(isset(request()->owner_user_id) && Bidders::where('acct_id', request()->owner_user_id)->exists() ){
                 //update
                 $bidder_id =  Bidders::where('acct_id', request()->owner_user_id)->first()->id;
                 $bidder = Bidders::find($bidder_id);
 
                 
               } else {
                 //create
                 $bidder = new Bidders();
                 
               }
               $bidder->acct_id = isset(request()->owner_user_id)?request()->owner_user_id:'';
               $bidder->name = isset(request()->posted_by)?request()->posted_by:'';
               $bidder->user_name = isset(request()->user_name)?request()->user_name:'';
               $bidder->email = isset(request()->owner_user_email)?request()->owner_user_email:'';
               $bidder->phone = isset(request()->phone_number)?request()->phone_number:'';
               $bidder->country = isset(request()->country)?request()->country:'';
               $bidder->status = 'active';
               $bidder->submitedfrom = isset(request()->submitedfrom)?request()->submitedfrom:'';
               $bidder->save();
               */
              
               /* for Bidders save only */
 
 
 
               
            
             if( $item->save() ){ // success
 
                 if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                     return redirect('/active-auctions/edit/'.$item->id)->with('added', $sucess_msg);
                 } else {
                     //submitted from shopify
                     echo json_encode(["status"=>'success','msg'=>'Succesfully added',"lotId"=>$lotid]);
                 }
 
             } else { // failed
                
                 
                 if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                     return redirect('/active-auctions')->with('failed', 'New item failed to add!');
                 } else {
                     //submitted from shopify
                     echo json_encode(["status"=>'failed','msg'=>'Failed to list',"lotId"=>$lotid]);
                 }
 
 
             }
                 
             
 
       }
       
        public function index(){

            $page = array(
                'menu'=>'active',
                'count'=>'active',
                'page_title'=>'Active Auction',
                'subtitle'=>'Active Items',
                'status'=>'active',
            );

            return view('admin.active-auctions')->with('page',$page);
        }



}
