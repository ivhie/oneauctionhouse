<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Services\ShopifyService;
use App\Models\Bidders;

class BiddersController extends Controller
{
        
    protected $shopifyService;

    public function __construct(ShopifyService $shopifyService )
    {
        $this->shopifyService = $shopifyService;
    }

    public function new($id=null){
            
            $page = array(
                'menu'=>'bidders',
                'subtitle'=>'Bidder Listing Form',
            );
            if($id){
                $bidder = Bidders::find( $id );
                $page['bidder'] = $bidder;
                $page['subtitle'] = 'Edit Bidder Entry';
            
            }
            
            return view('admin.bidders-new')->with('page',$page);

      }
      

      public function get(){

        $search_this = $_GET['search']['value'];
        $start = $_GET['start'];
        $length = $_GET['length'];
        $orderby = $_GET['order'][0]['column'];
        $orderdir = $_GET['order'][0]['dir'];
        
		$bidders = Bidders::select('*');
		$bidders->where('status','!=','deleted');
		//$users->where('branch_id','=',$branch_id);
		$bidders->orderBy('name', 'desc');
		
        $bidders2 = Bidders::select(array('id'));
		//$users2->where('branch_id','=',$branch_id);
		$bidders2->where('status','!=','deleted');
      
        if ($search_this) {
					
					$bidders->where('name','like', '%'.strtolower($search_this).'%');
                    $bidders->where('user_name','like', '%'.strtolower($search_this).'%');
                    $bidders->where('email','=',$search_this);
                    $bidders->where('phone','=',$search_this);
                    $bidders->where('acct_id','=',$search_this);

		}

        $bidders_count = $records_filtered = $bidders->get()->count();
        $records_filtered = $bidders->count();
        if ($search_this) {
            $records_filtered = $bidders->count();
        }

        if($orderby == 1)
            $orderby = 'name';
        else
            $orderby = 'id';
        
		$bidders = $bidders
            ->orderBy($orderby, $orderdir)
            ->skip( $start )->take($length)
            ->get();
        
        $data = array();

		if ($bidders)
		{
		      
              foreach($bidders as $k=>$bidder) {
               
				 $btn = '<!--<a class="btn  btn-primary btn-md btn-edit"  href="'.url('bidders/view/'.$bidder->id).'"><i class="fa fa-pencil" aria-hidden="true"></i>View</a>&nbsp;--><a class="btn  btn-success btn-md btn-edit"  href="'.url('bidders/edit/'.$bidder->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>&nbsp;<a class="btn  btn-danger btn-md btn-delete"  data-id="'.$bidder->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
				
				
                array_push($data,array(
						$bidder->id,
                        $bidder->acct_id,
						$bidder->name,
						$bidder->user_name,
                        $bidder->email,
						isset($bidder->phone)?$bidder->phone:'----',
                        isset($bidder->country)?$bidder->country:'----',
                        date('m/d/Y', strtotime($bidder->created_at)),
						$btn,
						
				));
              }
			  
              
		}
		echo json_encode(array('draw'=>$_GET['draw'],'recordsTotal'=>$bidders_count,'recordsFiltered'=>$records_filtered,'data'=>$data));
		
		
      }

      public function delete($id) {
		  
        $bidder =  Bidders::find($id);
        $bidder->status = 'deleted';
         if($bidder->save()) {
               echo json_encode(["msg"=>'success']);
          } else {
                echo json_encode(["msg"=>'failed']);
         }
            
     }
    
     public function generateRandomString($length = 4) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
      
     public function storeFromShopify(Request $request) {
      

        
        //$category = Bidders::where('user_name', $distance_id)
       // $consumers = $consumers->where('status','!=','DELETED');
        //->whereIn('cte_gender_id', $gender_id)->get();

         // $item = Bidders::find(request()->id);
        //  $message = 'Bidder/Seller  edited successfully!';
       
          //create
         // $item = new Bidders();
        //  $message = 'Bidder/Seller  added successfully!';

        $bidder = Bidders::query();
		$bidder = $bidder->where('acct_id','=',request()->acct_id);
        $bidder = $bidder->get();
         
        if(count($bidder)==0) {
           
            $item = new Bidders();
            $item->acct_id = request()->acct_id;
            $item->name = request()->name;
            $item->user_name = substr(request()->user_name, 0, 4).''.$this->generateRandomString();
            $item->email = request()->email;
            $item->submitedfrom = 'shopify';


            if( $item->save() ){ // success
                
                echo json_encode(["status"=>'success','msg'=>'Bidder/Seller  added successfully!']);
            } else { // failed
                
                echo json_encode(["status"=>'failed','msg'=>'Bidder/Seller  failed!']);
            }

        } else {

            echo json_encode(["status"=>'failed','msg'=>'Bidder/Seller  existed!']);

        }

    }

    public function getCpayments($acct_id){

        $bidder = Bidders::query();
		$bidder = $bidder->where('acct_id','=',request()->acct_id);
        $bidder = $bidder->where('status','!=','deleted');
        $bidder = $bidder->first();
        return response()->json( array("status"=>'success','msg'=>$bidder) );

    }



    public function storeBankDetailsUsingShopify(Request $request) {
      
        

        $bidder = Bidders::query();
		$bidder = $bidder->where('acct_id','=',request()->acct_id);
        $bidder = $bidder->first();
        //return response()->json( array("status"=>'failed','msg'=> $bidder->id) );
         
        //if(request()->id){
            //update
            $item = Bidders::find( $bidder->id);
            $message = 'Bidder/Seller  edited successfully!';
       // } else {
            //create
        //    $item = new Bidders();
         //   $message = 'Bidder/Seller  added successfully!';
           
          //}


         
       if($item) {
           
            //$item = new Bidders();
            $item->pbank_name = request()->pbank_name;
            $item->paccnt_holder_name = request()->paccnt_holder_name;
            $item->paccnt_number = request()->paccnt_number;
            $item->prouting_number = request()->prouting_number;
            $item->pswift_code = request()->pswift_code;
            $item->pbank_address = request()->pbank_address;
            $item->pbank_generate_code = substr(request()->acct_id, 0, 10).''.$this->generateRandomString();


            if( $item->save() ){ // success
                
               // echo json_encode(["status"=>'success','msg'=>'Bidder  updated successfully!']);
               return response()->json( array("status"=>'success','msg'=>$message) );
            } else { // failed
                
                //echo json_encode(["status"=>'failed','msg'=>'Bidder  failed!']);
                return response()->json( array("status"=>'failed','msg'=>'Bidder/Seller  failed!') );
            }

        } else {

            //echo json_encode(["status"=>'failed','msg'=>'Bidder/Seller  not found!']);

        }
            

    }
  

      public function store(Request $request) {
            if(request()->id){
               
                $validate = Validator::make($request->all(), [
              
                    'name' => 'required',
                    'acct_id' => 'required',
                    'user_name' => 'required',
                    'email' => 'required',
                   
                ],[
                    'name.required' => 'Name is required',
                    'acct_id.required' => 'Shopify Account ID is required',
                    'user_name.required' => 'Username is required',
                    'email.required' => 'Email is required',
                
                ]);


                
            } else {
              
                $validate = Validator::make($request->all(), [
              
                    'name' => 'required',
                    'acct_id' => 'required|unique:bidders',
                    'user_name' => 'required|unique:bidders',
                    'email' => 'required|unique:bidders',
                    //'phone' => 'unique:bidders',
                    //'country' => 'required',
                  
    
                ],[
                    'name.required' => 'Name is required',
                    'acct_id.required' => 'Shopify Account ID is required',
                    'acct_id.unique' => 'Shopify Account ID is already exist',
                    'user_name.required' => 'Username is required',
                    'user_name.unique' => 'Username is already exist',
                    'email.required' => 'Email is required',
                    'email.unique' => 'Email Address is already exist',
                    //'phone.unique' => 'Phone number is already exist',
    
    
                ]);


            }
             
          
            if($validate->fails()){
                return back()->withErrors($validate->errors())->withInput();
            }



            if(request()->id){
              //update
              $item = Bidders::find(request()->id);
              $message = 'Bidder/Seller  edited successfully!';
            } else {
              //create
              $item = new Bidders();
              $message = 'Bidder/Seller  added successfully!';
             
            }


            
            $item->acct_id = request()->acct_id;
            $item->name = request()->name;
            $item->user_name = request()->user_name;
            $item->email = request()->email;
            $item->phone = request()->phone;
            $item->country = request()->country;
            $item->verified = request()->verified;
            $item->submitedfrom = isset(request()->submitedfrom)?request()->submitedfrom:'';

            $item->pbank_name = request()->pbank_name;
            $item->paccnt_holder_name = request()->paccnt_holder_name;
            $item->paccnt_number = request()->paccnt_number;
            $item->prouting_number = request()->prouting_number;
            $item->pswift_code = request()->pswift_code;
            $item->pbank_address = request()->pbank_address;
            //$item->status = 'active';
          
           
            if( $item->save() ){ // success

                if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                    return redirect('/bidders')->with('added', $message);
                } else {
                    //submitted from shopify
                    echo json_encode(["status"=>'success','msg'=>$message]);
                }

            } else { // failed
               
                
                if(request()->submitedfrom == 'laravel'){ //submitted from laravel
                    return redirect('/bidders')->with('failed', 'New Bidder failed to add!');
                } else {
                    //submitted from shopify
                    echo json_encode(["status"=>'failed','msg'=>'New Bidder failed']);
                }


            }
            

      }
      
   
     
    public function getCustomers(){
         $users_html = '';
         $users = $this->shopifyService->getUsers();
        // var_dump($this->getLaravelCustomerRecord(7445465759788));
         //var_dump($this->shopifyService->getUser(7445465759788));
        if(isset($users['customers'])) { 
                for( $j=0; $j<count($users['customers']); $j++) { 

                    //$customer = $this->shopifyService->getUser($users['customers'][$j]['id']);
                    //$users_html .=var_dump($customer['customer']);
                    $larauser = $this->getLaravelCustomerRecord($users['customers'][$j]['id']);
                    if($larauser){
                        $user_id = $larauser->id;
                        $name = $larauser->name;
                        $user_name = $larauser->name;
                        $email = $larauser->email;
                        $verified = $larauser->verified;
                    } else {
                       
                        $name = '';
                        $user_name = '';
                        $name = '';
                        $email = '';
                        $verified = '';
                        $user_id = '';

                    }

                   $phone = substr($users['customers'][$j]['note'], 7);
                   $country = $users['customers'][$j]['addresses'][0]['country'];
                   $param = '?phone='. $phone.'&country='.$country;

                    //$users_html .='<tr>';
                    //$users_html .=var_dump($larauser);
                    $users_html .='<tr>';
                    $users_html .='<td>'.$users['customers'][$j]['id'].'</td>';
                    $users_html .='<td>'.$name.'</td>';
                    $users_html .='<td>'.$user_name.'</td>';
                    $users_html .='<td>'.$email.'</td>';
                    $users_html .='<td>'.substr($users['customers'][$j]['note'], 7).'</td>';
                    $users_html .='<td>'.$users['customers'][$j]['addresses'][0]['country'].'</td>';
                    $users_html .='<td>'.date('m/d/Y', strtotime($users['customers'][$j]['created_at'])).'</td>';
                    $users_html .='<td>'.ucfirst($verified).'</td>';
                    $users_html .='<td><a class="btn btn-primary" href="bidders/edit/'.$user_id.''.$param.'">Edit</a></td>';
                    $users_html .='</tr>';

                   
                
                }
        }

        

       return $users_html;

    }
    
    public function getLaravelCustomerRecord($acct_id){
       //var_dump($acct_id);
        //$bidder = Bidders::query();
        //$bidder = Bidders::select('*');
		//$bidder = $bidder->where('acct_id','=',$acct_id);
        //$bidder = $bidder->where('status','=','active');
        //$bidder = $bidder->first();
        //$bidder = $bidder->get();
        //return $bidder;
        $bidder = DB::table('bidders')->where('acct_id', $acct_id)->first();
       return $bidder;
    }

    public function getCustomer(){
        return $this->shopifyService->getCustomer();
    }

    public function getBillders2(){
        $users = $this->shopifyService->getUsers();
        $bidders_data = array();
        if(isset($users['customers'])) { 
            for( $j=0; $j<count($users['customers']); $j++) { 
                //var_dump($j);
                //$bidders_data[$j]['acct_id'] =
                //$shopifyid = $users['customers'][$j]['id'];
                $bidders_data[$users['customers'][$j]['id']]['acct_id'] = $users['customers'][$j]['id'];
                $bidders_data[$users['customers'][$j]['id']]['country'] = $users['customers'][$j]['addresses'][0]['country'];
                $bidders_data[$users['customers'][$j]['id']]['phone'] = substr($users['customers'][$j]['note'], 7);
              
                //$bidders_data[$j]['acct_id'] = $users['customers'][$j]['id'];
                //$bidders_data[$j]['country'] = $users['customers'][$j]['addresses'][0]['country'];
                //$bidders_data[$j]['phone'] = 'teserer';
                /*
                $larauser = $this->getLaravelCustomerRecord($users['customers'][$j]['id']);
                if($larauser){
                   
                    $bidders_data[$j]['user_id'] = $larauser->id;
                    $bidders_data[$j]['name'] = $larauser->name;
                    $bidders_data[$j]['email'] = $larauser->email;
                    $bidders_data[$j]['user_name'] = $larauser->email;

                } else {
                   
                    $bidders_data[$j]['user_id'] = '';
                    $bidders_data[$j]['name'] = '';
                    $bidders_data[$j]['email'] = '';
                    $bidders_data[$j]['user_name'] = '';

                }*/
            
            }
            //var_dump(count($bidders_data));
           // return $bidders_data;
        }

         //var_dump(count($bidders_data));
         return $bidders_data;

    }
    
    public function index(){
       
       $users = $this->shopifyService->getUsers();
       //var_dump($users);
       
        $page = array(
            'menu'=>'bidders',
            'bidders_content'=>$this->getCustomers(),
        );

    
        //var_dump($products);
       return view('admin.bidders')->with('page',$page);
        

    }

   

   
}