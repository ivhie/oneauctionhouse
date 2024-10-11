<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Bidders;

//use Illuminate\Support\Facades\Mail;
//use App\Models\EmailTemplate;

class EmailTemplate extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emailtemplate';
    protected $fillable = [
        'title',
        'body',
        'status',
    ];

    
    public function html_bidder_email($email=null) {

        echo 'ddddddddddddddd';
           /*  $email = array(
                'email_code'=>1,
                'seller_name'=>'ivandolera',
                'lot_number'=>'8444',
                'seller_email'=>'ivan_dolera@yahoo.com',
            );

            */
            

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

                
                $message->to($email['seller_email'], $email['seller_name'])->subject($subject);
                $message->cc(env('ADMIN_EMAIL'), 'One Auction House')->subject($subject);
            
            });

            return 'testerer';
            
    }

    // public function html_admin_email($email_code,$admin_email) {
    public function html_admin_email($email=null) {
        
        /*
            $email = array(
                'email_code'=>'',
                'lot_number'=>'123',
                'lot_name'=>'123',
                'lot_sold_price'=>'111',
                'brand_name'=>'bradd',
                'seller_name'=>'Ivan Dolera',
                'seller_email'=>'seller_wire_details',
                'receiver_name'=>'Ivan Dolera',
                'lot_highest_bid_price'=>'111',
                'here'=>'111',
                //'ACTION'=>'[ACTION]',
                'attach-pdf'=>'111',
                'TodayPlus3days'=>'111',
                'bid_place_time'=>'111',
                'auction_end_time'=>'111',
                'buyer_name'=>'111',
               
                
                
            );
            */
            
            /*
            $emaildata = EmailTemplate::find($email['email_code']);
            

            $ubject = $emaildata->title; 
            $body = $emaildata->body;
            //$text = "Hello, [username]! This is a test! [foo]";
            foreach ($email as $k => $v) $ubject = str_ireplace("[".$k."]", $v, $ubject);

            foreach ($email as $k => $v) $body = str_ireplace("[".$k."]", $v, $body);
        
            $email_content = array('email_body'=>$body,'email'=>$email);

            Mail::send('admin.email-content', $email_content, function($message) {
                $message->to($admin_email, 'One Auction House Admin')->subject($ubject);
            });
            */
        //echo "HTML Email Sent. Check your inbox.";
    }


    public function attachment_email() {
        $data = array('name'=>"Virat Gandhi");
        Mail::send('mail', $data, function($message) {
           $message->to('abc@gmail.com', 'Tutorials Point')->subject
              ('Laravel Testing Mail with Attachment');
           $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
           $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
           $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Email Sent with attachment. Check your inbox.";
     }


     public function  emailSend(MailerInterface $mailer) : Response {

        $email = (new Email())
        ->from('hello@example.com')
        ->to('ivandolera24@gmail.com')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Time for Symfony Mailer!')
        ->text('Sending emails is fun again!')
        ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);


     }



}
