<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\File;
//use Illuminate\Support\Facades\Storage;
//use Image;
//use  URL;
//use Mail;
use Illuminate\Support\Facades\Mail;
//use Illuminate\Mail\Mailer;

//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Mailer\MailerInterface;
//use Symfony\Component\Mime\Email;
//use Symfony\Component\Routing\Annotation\Route;

//use Illuminate\Support\Facades\Mail;

//use ZipArchive;

//use App\Services\ShopifyService;
//use App\Models\User;
//use App\Models\AuctionItems;
//use App\Models\Bidders;
//use App\Models\Bidding;
//use App\Models\Fulfillment;
use App\Models\EmailTemplate;



class EmailTemplateController extends Controller
{
    

       public function new($id=null){
             
            $page = array(
                'menu'=>'email-template',
                'subtitle'=>'Email Template Entry',
            );
            if($id){
                $emailtemplate = EmailTemplate::find( $id );
                $page['emailtemplate'] = $emailtemplate;
                $page['subtitle'] = 'Edit Email Template Entry';
            
            }
            
            return view('admin.email-template-new')->with('page',$page);
 
       }
       
     
       
 
       public function get(){
 
         $search_this = $_GET['search']['value'];
         $start = $_GET['start'];
         $length = $_GET['length'];
         $orderby = $_GET['order'][0]['column'];
         $orderdir = $_GET['order'][0]['dir'];
         
         
         $emailtemplates = EmailTemplate::select('*');
         $emailtemplates->where('status','!=','deleted');
         $emailtemplates->orderBy('id', 'asc');
        
 
     
         $emailtemplates2 = EmailTemplate::select(array('id'));
         $emailtemplates2->where('status','!=','deleted');
         $emailtemplates2->where('id','=','asc');
       
         if ($search_this) {
                     
                     //$users->where('watch_lot_id','like', '%'.strtolower($search_this).'%');
                     $emailtemplates->where('title','=',$search_this);
 
         }
 
         $emailtemplates_count = $records_filtered = $emailtemplates->get()->count();
         $records_filtered = $emailtemplates->count();
         if ($search_this) {
             $records_filtered = $emailtemplates->count();
         }
 
         if($orderby == 1)
             $orderby = 'title';
         else
             $orderby = 'id';
         
         $emailtemplates = $emailtemplates
             ->orderBy($orderby, $orderdir)
             ->skip( $start )->take($length)
             ->get();
         
         $data = array();
 
         if ($emailtemplates)
         {
               
               foreach($emailtemplates as $k=>$emailtemplate) {
                
                 
                
                  $btn = '<a class="btn  btn-success btn-md btn-edit"  href="'.url('email-template/edit/'.$emailtemplate->id).'"><i class="fa fa-pencil" aria-hidden="true"></i> View</a>&nbsp;<a class="btn  btn-danger btn-md btn-delete"  data-id="'.$emailtemplate->id.'" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                 
                 
                 array_push($data,array(
                         $emailtemplate->id,
                         'email_temp_'.$emailtemplate->id,
                         $emailtemplate->title,
                         $btn,
                         
                     ));
               }
               
               
         }
         echo json_encode(array('draw'=>$_GET['draw'],'recordsTotal'=>$emailtemplates_count,'recordsFiltered'=>$records_filtered,'data'=>$data));
         
         
       }
 
       public function delete($id) {
           
         $emailtemplate =  EmailTemplate::find($id);
         $emailtemplate->status = 'deleted';
          if($emailtemplate->save()) {
                echo json_encode(["msg"=>'success']);
           } else {
                 echo json_encode(["msg"=>'failed']);
          }
             
      }
 
       
     
 
       public function store(Request $request) {
             
            $validate = Validator::make($request->all(), [
                
                'title' => 'required',
                'body' => 'required',
               

            ],[
                'title.required' => 'Title is required',
                'body.required' => 'Body is required',
               
            ]);
            if($validate->fails()){
                return back()->withErrors($validate->errors())->withInput();
            }

             
             if(request()->id){
               //update
               $emailtemplate = EmailTemplate::find(request()->id);
               $message = 'Email template updated successfully!';
               
             } else {
               //create
               $emailtemplate = new EmailTemplate();
               $message = 'Email template added successfully!';
             }
               
             
           
           
             $emailtemplate->title = isset(request()->title)?request()->title:'';
             $emailtemplate->body = isset(request()->body)?request()->body:'';
             $emailtemplate->note = isset(request()->note)?request()->note:'';
             $emailtemplate->status = 'active';
           
            
             

             if( $emailtemplate->save() ){ // success

                
                    return redirect('/email-template/edit/'.$emailtemplate->id)->with('added', $message);
                

            } else { // failed
               
                    return redirect('/email-template/edit/'.$emailtemplate->id)->with('failed', 'Email failed to update!');
              


            }
                 
             
 
       }
       
        public function index(){

            $page = array(
                'menu'=>'email-template',
                'page_title'=>'Email Templates',
                'subtitle'=>'Email Template',
                'status'=>'active',
            );

            return view('admin.email-template')->with('page',$page);
        }


        public function emailtesting(){

            $page = array(
                'menu'=>'email-template-test',
                'page_title'=>'Email Testing',
                'subtitle'=>'Email Testing',
                'status'=>'active',
            );

            return view('admin.email-testing-new')->with('page',$page);
        }




    public function html_bidder_email($email=null) {
          /* $email = array(
                'email_code'=>1,
                'seller_name'=>'ivandolera',
                'lot_number'=>'855',
                'seller_email'=>'ivan_dolera@yahoo.com',
                //'seller_email'=>'support@z9j.9f8.mytemp.website',
            );
            
           // $EmailTemplate = new EmailTemplate;
           //echo 'ffffffffffffbbbb'. $EmailTemplate->html_admin_email($email);
          //  echo 'email sent';

           /*
            $email = array(
                'email_code'=>'',
                'lot_number'=>'123',
                'lot_name'=>'123',
                'lot_sold_price'=>'111',
                'brand_name'=>'bradd',
                'seller_name'=>'Ivan Dolera',
                'seller_email'=>'111',
                'receiver_name'=>'Ivan Dolera',
                'lot_highest_bid_price'=>'111',
                'here'=>'111',
                'ACTION'=>'[ACTION]',
                'attach-pdf'=>'111',
                'TodayPlus3days'=>'111',
                'bid_place_time'=>'111',
                'auction_end_time'=>'111',
                'buyer_name'=>'111',
                'buyer_name'=>'seller_wire_details',
                
                
            );
            */

            $emaildata = EmailTemplate::find($email['email_code']);
            

            $subject = $emaildata->title; 
            $body = $emaildata->body;
            //$text = "Hello, [username]! This is a test! [foo]";
            foreach ($email as $k => $v) $subject = str_ireplace("[".$k."]", $v, $subject);

            foreach ($email as $k => $v) $body = str_ireplace("[".$k."]", $v, $body);
        
            $email_content = array('email_body'=>$body);

            //$to = $email['seller_email'];
            //$seller_name = $email['seller_name'];
            Mail::send('admin.email-content', $email_content, function($message) use ($email, $subject) {

                $message->from(env('FROM_EMAIL'), env('FROM_EMAIL_NAME'));
                $message->to($email['seller_email'], $email['seller_name'])->subject($subject);
                $message->cc(env('ADMIN_EMAIL'), 'One Auction House')->subject($subject);
            
            });

           
    }

    public function html_admin_email($email=null) { // reserve para sa way angay



    }

     public function email_send1() { // reserve para sa way angay

            // the message
            $msg = "First line of text\nSecond line of text";
            
            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg,70);
            
            // send email
            mail("ivandolera24@gmail.com","My subject",$msg);
             echo 'testererererer';
    }

   




     

       



}
