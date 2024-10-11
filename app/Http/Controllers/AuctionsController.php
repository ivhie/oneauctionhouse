<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Shopify\Clients\Graphql;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Image;
use  URL;
//use ZipArchive;

use App\Services\ShopifyService;
use App\Models\User;
use App\Models\AuctionItems;
use App\Models\Bidders;
use App\Models\Bidding;

use App\Http\Controllers\EmailTemplateController;

class AuctionsController extends Controller
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
        
       /*
        public function updateMetaField($productId,$metafieldId){
                 
            $watch_brand_meta_id = $this->shopifyService->getMetaIDByKey($productId,'watch_brand');
                
           
            $response[] = $this->shopifyService->updateMeta(
               $auction->productId,
               $watch_brand_meta_id,
               'watch_brand',
               'tester',
               'single_line_text_field'
           );


        }*/

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
                "images" => [
                        [
                            "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/bvlgari-octo_finissimo_tadao_a_1708853405_2169b958_progressive_d7535405-0fcb-4250-a648-26d144e8fd29.jpg?v=1722193663"  // Default image URL
                       ] ,
                       [
                         "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/bvlgari-octo_finissimo_tadao_a_1708853405_2169b958_progressive_d7535405-0fcb-4250-a648-26d144e8fd29.jpg?v=1722193663"  // Default image URL
                    ] 
                 ],

               /*
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
                        "value" => $auction->reserves,
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
                        "key" => "bidding_description",
                        "value" => $auction->bidding_description,
                        "type" => "multi_line_text_field"
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
                    ]

                ]*/



            ];
    
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
                  $auction->reserves,
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
              
              





                //update meta field
                
               /*if($this->shopifyService->getAllProductMeta($auction->product_id)) {

                   foreach ( $this->shopifyService->getAllProductMeta($auction->product_id) as $meta ){

                       $response[] = $this->shopifyService->updateMeta(
                            $auction->product_id,
                            $meta->id,
                            $meta->key,
                            $meta->value,
                            $meta->type
                       );
                   }

               }*/






                
                //$response = $client->request('GET', "products/{$productId}/metafields.json");  
                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
        }


        public function publishedAuction($id){
            
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
                "images" => [
                       [
                           "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/bvlgari-octo_finissimo_tadao_a_1708853405_2169b958_progressive.jpg"  // Default image URL
                      ] ,
                      [
                        "src" => "https://cdn.shopify.com/s/files/1/0632/0862/0076/files/cheapest_bvlgari_octo_finissi_1709603051_2499b232_progressive.jpg"  // Default image URL
                   ] 
                ],

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
                        "value" => $auction->reserves,
                        "type" => "single_line_text_field"
                    ],
                    [
                        "namespace" => "custom",
                        "key" => "bidding_title",
                        "value" => $auction->bidding_title,
                        "type" => "single_line_text_field"
                    ],
                   /* [
                        "namespace" => "custom",
                        "key" => "bidding_description",
                        "value" => $auction->bidding_description,
                        "type" => "multi_line_text_field"
                    ],*/
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
                    ]

                ]



            ];
    
            try {

                //if(!$auction->product_id) {

                    $response = $this->shopifyService->createProduct($productData);
                    //save product id
                    $auction->product_id = $response['product']['id'];
                    $auction->product_url = $response['product']['handle'];
                    $auction->save();
                //} else {
                //    $response = $this->shopifyService->updateProduct($auction->product_id,$productData);
                    //$response = $auction->product_id;
               // }
               

                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            //return response()->json($response);
            //return redirect('/auctions')->with('status', 'Auction Item Added Succesfully Added!');
        }


        public function createAuction(Request $request){
          
            $productData = [
                'title' => request()->watch_brand,
                'bodyHtml' => '<p>Good product!</p>',
                'vendor' => 'Vendor Name',
                'status' => 'draft',
                'productType' => 'Product Type',
                'variants' => [
                    [
                        'price' => '19.99'
                    ]
                ]
            ];
    
            try {
                $response = $this->shopifyService->createProduct($productData);
                return response()->json($response);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            
            //return response()->json($response);
            return redirect('/auctions')->with('status', 'Auction Item Added Succesfully Added!');
      }




      public function new($id=null){
            
            $page = array(
                'menu'=>'auctions',
                'page_title'=>'Auction Listing',
                'subtitle'=>'Auction Entry',
                'status'=>'auctions',
                'bidders'=>$this->bid->getBidders(),
                //'bidders'=>  AuctionItems::getBillders2(),
                'bidders2'=>app('App\Http\Controllers\BiddersController')->getBillders2()
                //'bidders'=>$this->shopifyService->getUsers(),
                //$users = $this->shopifyService->getUsers()
            );
            //var_dump(count(app('App\Http\Controllers\BiddersController')->getBillders2()));
            if($id){
                $auction = AuctionItems::find( $id );
                $page['auction'] = $auction;
                $page['subtitle'] = 'Edit Auction Entry';
                $page['bidderD'] = $this->b->getBidder($auction->owner_user_id);
                //var_dump( $this->b->getBidder($auction->owner_user_id));
                // set up url on this part for watch photos
                //var_dump($page['bidderD']);
                $urls = array();
                if( isset($auction ->watch_photos) ) { 

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

               


            }
            //$url = Storage::disk('public')->url($filePath);
            //var_dump(public_path('storage'));
            return view('admin.auctions-new')->with('page',$page);

      }
      
    
      public function download($lotid=null){
            
            $zip_file = 'Lot-'.$lotid.'.zip';
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            //$path = storage_path('invoices');
            $path = public_path('watches/lot-'.$lotid); //download all images base from lotid
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
        $auctions->where('product_status','=','pending');
		//$users->where('branch_id','=',$branch_id);
		$auctions->orderBy('watch_lot_id', 'desc');
       

    
        $auctions2 = AuctionItems::select(array('id'));
		//$users2->where('branch_id','=',$branch_id);
		$auctions2->where('status','!=','deleted');
        $auctions2->where('product_status','=','pending');
      
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
                /*
				if($auction->status=="Inactive"){
				
				    $status='<span style="color:red;font-weight:bold;">
							 Inactive
							</span>';
					
				} else if($auction->status=="Active"){
				
				     $status='<span style="color:green;font-weight:bold;">
							 Active
							</span>';
				  
				}*/

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
                    $viewlineshopify = '<a class="btn btn-warning" target="_blank" href="https://'.env("SHOPIFY_SHOP_DOMAIN").'/products/'.$auction->product_url.'">View Shopify</a>&nbsp;';
                    
                }
				 $btn = '<!--<a class="btn  btn-primary btn-md btn-edit"  href="'.url('auctions/view/'.$auction->id).'"><i class="fa fa-pencil" aria-hidden="true"></i>View</a>&nbsp;-->'.$viewlineshopify.'<a class="btn  btn-success btn-md btn-edit"  href="'.url('auctions/edit/'.$auction->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;<a class="btn  btn-danger btn-md btn-delete"  data-id="'.$auction->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
				
				
                array_push($data,array(
						$auction->id,
						'Lot#'.$auction->watch_lot_id,
						$auction->bidding_title,
                        '$'.$newbidamt,
						isset($auction->product_status)?$auction->product_status:'----',
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
         if($auction->save()) {
               echo json_encode(["msg"=>'success']);
          } else {
                echo json_encode(["msg"=>'failed']);
         }
            
     }

      
    

      public function AddAuctionToLaravelDB(Request $request) : JsonResponse 
      
      {
            
       
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


            
            /* Process notable photo with scratches */
            if($request->hasFile('watch_photos_notable_damage')) {
                $n= 0;
                foreach ($request->file('watch_photos_notable_damage') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'watch_photos_notable_damage-'.$n.'-'.time(). '.' .$extension;
                    $destinationPath = public_path('watches/shopify/lot-'.$lotid);
                    $file->move($destinationPath, $filename);
                    $watch_photos_notable_damage_data[] = $filename;
                    $n++;
                }
            }

            if(isset($watch_photos_notable_damage_data) && !empty($watch_photos_notable_damage_data)) {
                $watch_photos_notable_damage_data = (implode(",", $watch_photos_notable_damage_data));
            } else {
                $watch_photos_notable_damage_data = '';
            }


             /* UPload Process watch accessories */
             if($request->hasFile('watch_photos_accessories')) {
                $n= 0;
                foreach ($request->file('watch_photos_accessories') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename =  'watch_photos_accessories-'.$n.'-'.time(). '.' .$extension;
                    $destinationPath = public_path('watches/shopify/lot-'.$lotid);
                    $file->move($destinationPath, $filename);
                    $watch_photos_accessories_data[] = $filename;
                    $n++;
                }
            }

            if(isset($watch_photos_accessories_data) && !empty($watch_photos_accessories_data)) {
                $watch_photos_accessories_data = (implode(",", $watch_photos_accessories_data));
            } else {
                $watch_photos_accessories_data = '';
            }

            /* Upload process watch with drivers license */
            if($request->hasFile('watch_photos_id_drivers_license')) {
                $n= 0;
                foreach ($request->file('watch_photos_id_drivers_license') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'watch_photos_id_drivers_license-'.$n.'-'.time(). '.' .$extension;
                    $destinationPath = public_path('watches/shopify/lot-'.$lotid);
                    $file->move($destinationPath, $filename);
                    $watch_photos_id_drivers_license_data[] = $filename;
                    $n++;
                }
            }

            if(isset($watch_photos_id_drivers_license_data) && !empty($watch_photos_id_drivers_license_data)) {
                $watch_photos_id_drivers_license_data = (implode(",", $watch_photos_id_drivers_license_data));
            } else {
                $watch_photos_id_drivers_license_data = '';
            }
           
              
          
            // Process the form data
            //$watch_photos_data = [];
            //Process watch photos
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






           
            $item->watch_brand = isset(request()->watch_brand)?request()->watch_brand:'';
            $item->watch_model_number = isset(request()->watch_model_number)?request()->watch_model_number:'';
            $item->year_of_watch = isset(request()->year_of_watch)?request()->year_of_watch:'';
            $item->watch_package = isset(request()->watch_package)?request()->watch_package:'';
            $item->watch_papers = isset(request()->watch_papers)?request()->watch_papers:'';
            $item->watch_box = isset(request()->watch_box)?request()->watch_box:'';
            $item->watch_after_market = isset(request()->watch_after_market)?request()->watch_after_market:'';
            $item->watch_condition = isset(request()->watch_condition)?request()->watch_condition:'';
            $item->reserves = isset(request()->watch_reserve)?request()->watch_reserve:0;
            $item->uploadphotos = isset(request()->uploadphotos)?request()->uploadphotos:'';
            $item->bidding_date = isset(request()->bidding_date)?request()->bidding_date:'';
            $item->bidding_time = isset(request()->bidding_time)?request()->bidding_time:'';
            $item->bidding_title = isset(request()->bidding_title)?request()->bidding_title:'';
            $item->bidding_description = isset(request()->bidding_description)?request()->bidding_description:'';
            
            //save those photos
            if($watch_photos_notable_damage_data){
                $item->watch_photos_notable_damage = $watch_photos_notable_damage_data;
            }
            if($watch_photos_accessories_data){
                $item->watch_photos_accessories = $watch_photos_accessories_data;
            }
            if($watch_photos_id_drivers_license_data){
                $item->watch_photos_id_drivers_license = $watch_photos_id_drivers_license_data;
            }

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
        
              
           
            if( $item->save() ){ // success

                
                $lotid = $lotid;
                $status = 'success';

                //sending email
                //send email using template 1
                $bidder_id =  Bidders::where('acct_id', request()->owner_user_id)->first()->id;
                $bidder = Bidders::find($bidder_id);
                $email = array(
                    'email_code'=>'1',
                    'seller_name'=>$bidder->name,
                    'seller_email'=>$bidder->email,
                    'lot_number'=>$lotid,
                );
                $this->sendingEmail->html_bidder_email($email);
                $this->sendingEmail->html_admin_email($email);
                


            } else { // failed
               
            
                 $lotid = '00';
                 $status = 'failed';
            }
                
            $data = [
                "lotId"=>$lotid,
                "status"=>$status

            ];
    
            
            return response()->json($data);

          



      }
      
   


    public function index(){
       
        
        //$AuctionItems = new AuctionItems();
       //$this->lot->LotNumber();
        //var_dump($this->lot->LotNumber());
       
       /*
        $accessToken = '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'; // Replace with your actual access token
        $shopifyStore = '76c14a-0b.myshopify.com'; // Replace with your Shopify store URL
        $apiVersion = '2024-07'; // Replace with the API version you are targeting

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://$shopifyStore/admin/api/$apiVersion/products.json");

        // Check if request was successful (status code 200)
        if ($response->successful()) {
            $products = $response->json()['products'];

            $page = array(
                'menu'=>'auctions',
                'auctions'=>$products
            );
            //var_dump($products);
            return view('admin.auctions')->with('page',$page);
            //if($products = $response->json()['products'])
            // Process $products as needed
           // return response()->json($products);
        } else {
            // Handle the error response
            $errorCode = $response->status();
            $errorMessage = $response->json()['errors']; // Example of extracting error message
            return response()->json(['error' => "Error $errorCode: $errorMessage"], $errorCode);
        }
       */
        if (request()->ajax()) {
            $auctions = AuctionItems::query();
            return DataTables::of($auctions)->make(true);
        }

        $page = array(
            'menu'=>'auctions',
            'page_title'=>'Auction Listing',
            'subtitle'=>'Auction List',
            //'auctions'=>$auctions
        );
        //var_dump($products);
        return view('admin.auctions')->with('page',$page);



       /*
        $page = array(
            'menu'=>'auctions',
            'auctions'=>$products
        );
        //var_dump($products);
        return view('admin.auctions')->with('page',$page);
      */


        //return view('admin.auctions');
    }

    /*
    public function unfulfilled(){

        $page = array(
            'menu'=>'unfulfilled',
            'page_title'=>'Unfulfilled Auctions',
            'subtitle'=>'Unfulfilled Items',
            'status'=>'unfulfilled',
        );

        return view('admin.auctions')->with('page',$page);
    }




    public function pending(){

        $page = array(
            'menu'=>'pending',
            'page_title'=>'Pending Approvals',
            'subtitle'=>'Pending Items',
            'status'=>'pending',
        );

        return view('admin.auctions')->with('page',$page);
    }

    public function acitve(){

        $page = array(
            'menu'=>'active',
            'page_title'=>'Active Auctions',
            'subtitle'=>'Pending Items',
            'status'=>'active',
        );

        return view('admin.auctions')->with('page',$page);
    }

    public function completed(){

        $page = array(
            'menu'=>'completed',
            'page_title'=>'Completed Auctions',
            'subtitle'=>'Completed Items',
            'status'=>'completed',
        );

        return view('admin.auctions')->with('page',$page);
    }

    public function postauct(){

        $page = array(
            'menu'=>'post-auction',
            'page_title'=>'Post-Auction Listings',
            'subtitle'=>'Post Listings',
            'status'=>'post',
        );

        return view('admin.auctions')->with('page',$page);
    }
    */


   
}
