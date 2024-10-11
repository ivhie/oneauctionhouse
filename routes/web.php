<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ShopifyController;
//use App\Http\Controllers\ImageController;

//use App\Http\Controllers\ShopifyController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
    //return view('welcome');
//});

Route::get('/products', [ShopifyController::class, 'getProducts']);
/*
Route::get('shopify/install', [ShopifyController::class, 'install'])->name('shopify.install');
Route::get('shopify/callback', [ShopifyController::class, 'callback'])->name('shopify.callback');

Route::middleware(['shopify.auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    // other protected routes
});

Route::get('logout', function () {
    Session::flush();
    return redirect()->route('shopify.install');
})->name('logout');


Route::get('/', function () {
    return view('home-shopify');
})->middleware(['verify.shopify'])->name('home');

*/


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//use App\Http\Controllers\AuctionFormSubmitShopifyController;

use Illuminate\Support\Facades\Mail;

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    /**
     * Home Routes
     */
    //Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
        //Route::get('/register', 'RegisterController@show')->name('register.show');
        //Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/', 'LoginController@show')->name('login.show');
        Route::post('/', 'LoginController@login')->name('login.perform');

        
    });

    Route::group(['middleware' => ['auth']], function() {
        /**
         * Logout Routes
         */
        Route::get('/login', 'LogoutController@perform')->name('logout.perform');
       
        /* Auction Listing */
        //Route::get('/unfulfilled-auctions', 'AuctionsController@unfulfilled');
        
        //Route::get('/acitve-auctions', 'AuctionsController@acitve');
        //Route::get('/completed-auctions', 'AuctionsController@completed');
        //Route::get('/post-auctions', 'AuctionsController@postauct');


        /* Unfulfilled Auctions */
        Route::get('/unfulfilled-auctions', 'UnfulfilledAuctionsController@index');
        Route::get('/unfulfilled-auctions/get', 'UnfulfilledAuctionsController@get');
        Route::get('/unfulfilled-auctions/new', 'UnfulfilledAuctionsController@new');
        Route::post('/unfulfilled-auctions/store', 'UnfulfilledAuctionsController@store');
        Route::get('/unfulfilled-auctions/edit/{id}', 'UnfulfilledAuctionsController@new');
        Route::get('/unfulfilled-auctions/download/{lotId}/{pdf_filename}', 'UnfulfilledAuctionsController@download');
        Route::get('/unfulfilled-auctions/downloadPaymentFile/{lotId}/{pdf_filename}', 'UnfulfilledAuctionsController@downloadPaymentFile');
        //Route::delete('/unfulfilled-auctions/delete/{id}', 'UnfulfilledAuctionsController@delete');
        //Route::get('/unfulfilled-auctions/view/{id}', 'UnfulfilledAuctionsController@view');
        //Route::get('/unfulfilled-auctions/download/{lotId}', 'UnfulfilledAuctionsController@download');
        //Route::post('/unfulfilled-auction-post-lara-db', 'UnfulfilledAuctionsController@AddAuctionToLaravelDB');
        //Route::get('/unfulfilled-push-auction-live/{id}', 'UnfulfilledAuctionsController@publishedAuction');
        //Route::get('/unfulfilled-update-auction-live/{id}', 'UnfulfilledAuctionsController@UpdatePublishedAuction');

        /* Pending Approvals */
        Route::get('/pending-approvals', 'PendingApprovalsController@index');
        Route::get('/pending-approvals/get', 'PendingApprovalsController@get');
        Route::get('/pending-approvals/new', 'PendingApprovalsController@new');
        Route::get('/pending-approvals/edit/{id}', 'PendingApprovalsController@new');
        Route::delete('/pending-approvals/delete/{id}', 'PendingApprovalsController@delete');
        Route::get('/pending-approvals/view/{id}', 'PendingApprovalsController@view');
        Route::get('/pending-approvals/download/{lotId}', 'PendingApprovalsController@download');
        Route::post('/pending-auction-post-lara-db', 'PendingApprovalsController@AddAuctionToLaravelDB');
        Route::post('/pending-push-auction-live/save', 'PendingApprovalsController@publishedAuction');
        Route::get('/pending-update-auction-live/{id}', 'PendingApprovalsController@UpdatePublishedAuction');

        /* Active Auctions */
        Route::get('/active-auctions', 'ActiveAuctionsController@index');
        Route::get('/active-auctions/get', 'ActiveAuctionsController@get');
        Route::get('/active-auctions/new', 'ActiveAuctionsController@new');
        Route::get('/active-auctions/edit/{id}', 'ActiveAuctionsController@new');
        Route::delete('/active-auctions/delete/{id}', 'ActiveAuctionsController@delete');
        Route::get('/active-auctions/view/{id}', 'ActiveAuctionsController@view');
        Route::get('/active-auctions/download/{lotId}', 'ActiveAuctionsController@download');
        Route::post('/active-auction-post-lara-db', 'ActiveAuctionsController@AddAuctionToLaravelDB');
        Route::post('/active-push-auction-live/save', 'ActiveAuctionsController@publishedAuction');
        Route::get('/active-update-auction-live/{id}', 'ActiveAuctionsController@UpdatePublishedAuction');
       

         /* Completed Auctions */
        Route::get('/completed-auctions', 'CompletedAuctionsController@index');
        Route::get('/completed-auctions/get', 'CompletedAuctionsController@get');
        Route::get('/completed-auctions/new', 'CompletedAuctionsController@new');
        Route::get('/completed-auctions/edit/{id}', 'CompletedAuctionsController@new');
        Route::delete('/completed-auctions/delete/{id}', 'CompletedAuctionsController@delete');
        Route::get('/completed-auctionsview/{id}', 'CompletedAuctionsController@view');
        Route::get('/completed-auctions/download/{lotId}', 'CompletedAuctionsController@download');
        Route::post('/completed-auction-post-lara-db', 'CompletedAuctionsController@AddAuctionToLaravelDB');
        Route::post('/completed-push-auction-live/save', 'CompletedAuctionsController@publishedAuction');
        Route::get('/completed-update-auction-live/{id}', 'CompletedAuctionsController@UpdatePublishedAuction');

       
          /* Post Auctions */
          Route::get('/post-auctions', 'PostAuctionsController@index');
          Route::get('/post-auctions/get', 'PostAuctionsController@get');
          Route::get('/post-auctions/new', 'PostAuctionsController@new');
          Route::get('/post-auctions/edit/{id}', 'PostAuctionsController@new');
          Route::delete('/post-auctions/delete/{id}', 'PostAuctionsController@delete');
          Route::get('/post-auctionsview/{id}', 'PostAuctionsController@view');
          Route::get('/post-auctions/download/{lotId}', 'PostAuctionsController@download');
          Route::post('/post-auction-post-lara-db', 'PostAuctionsController@AddAuctionToLaravelDB');
          Route::post('/post-push-auction-live/save', 'PostAuctionsController@publishedAuction');
          Route::get('/post-update-auction-live/{id}', 'PostAuctionsController@UpdatePublishedAuction');


      
        Route::get('/auctions', 'AuctionsController@index');
        Route::get('/auctions/get', 'AuctionsController@get');
        Route::get('/auctions/new', 'AuctionsController@new');
        Route::get('/auctions/edit/{id}', 'AuctionsController@new');
        Route::delete('/auctions/delete/{id}', 'AuctionsController@delete');
        Route::get('/auctions/view/{id}', 'AuctionsController@view');
        Route::get('/auctions/download/{lotId}', 'AuctionsController@download');





     
        /* Post Auction to Shopify */
        Route::post('/auction-create', 'AuctionsController@createAuction');
        Route::get('/push-auction-live/{id}', 'AuctionsController@publishedAuction');
        Route::get('/update-auction-live/{id}', 'AuctionsController@UpdatePublishedAuction');
        //Route::get('/products/{productId}/metafields/{metafieldId}', 'AuctionsController@UpdatePublishedAuction');
        /* Post Auction to Laravel DB */
        Route::post('/auction-post-lara-db', 'AuctionsController@AddAuctionToLaravelDB');


        
       
        /* Biddings */
        Route::get('/bidding', 'BiddingController@index');
        Route::get('/bidding/get', 'BiddingController@get');
        Route::get('/bidding/new', 'BiddingController@new');
        Route::post('/bidding/store', 'BiddingController@store');
        Route::get('/bidding/edit/{id}', 'BiddingController@new');
        Route::delete('/bidding/delete/{id}', 'BiddingController@delete');
        Route::get('/bidding/view/{id}', 'BiddingController@view');
       
        /* Bidders */
       
        Route::get('/bidders/customer', 'BiddersController@getCustomer');// this is just a test only

        Route::get('/bidders', 'BiddersController@index');
        Route::get('/bidders/get', 'BiddersController@get');
        Route::get('/bidders/getshopifyusers', 'BiddersController@getCustomers');
        Route::get('/bidders/new', 'BiddersController@new');
        Route::post('/bidders/store', 'BiddersController@store');
        Route::get('/bidders/edit/{id}', 'BiddersController@new');
        Route::delete('/bidders/delete/{id}', 'BiddersController@delete');
        Route::get('/bidders/view/{id}', 'BiddersController@view');
       
        /* Comments */
        Route::get('/comments', 'CommentsController@index');
        Route::get('/comments/get', 'CommentsController@get');
        Route::get('/comments/new', 'CommentsController@new');
        Route::post('/comments/store', 'CommentsController@store');
        Route::get('/comments/edit/{id}', 'CommentsController@new');
        Route::delete('/comments/delete/{id}', 'CommentsController@delete');
        Route::get('/comments/view/{id}', 'CommentsController@view');

        /* Email Template */
        Route::get('/email-template', 'EmailTemplateController@index');
        Route::get('/email-template/get', 'EmailTemplateController@get');
        Route::get('/email-template/new', 'EmailTemplateController@new');
        Route::post('/email-template/store', 'EmailTemplateController@store');
        Route::get('/email-template/edit/{id}', 'EmailTemplateController@new');
        Route::delete('/email-template/delete/{id}', 'EmailTemplateController@delete');
        Route::get('/email-template/view/{id}', 'EmailTemplateController@view');

        
       
       // Email Sending
       Route::get('/emailbidder', 'EmailTemplateController@html_bidder_email');
       Route::get('/emailadmin', 'EmailTemplateController@html_admin_email');

       //Route::get('/email-send', 'EmailTemplateController@emailSend');
       //Route::get('/email-send-display/{email_code}', 'EmailTemplateController@html_bidder_email_test');

        // Email testing
        /*Route::get('/email-template-test', 'EmailTemplateController@emailtesting');
        Route::get('/email-template-sendme', 'EmailTemplateController@testsend');
        Route::get('/email-send', 'EmailTemplateController@emailSend');
        Route::get('/email-send2', 'EmailTemplateController@html_email');

    

        Route::get('/send-test-email', function () {
            Mail::raw('<h1>This is a test email from Laravel using GoDaddy SMTP.</h1>', function ($message) {
                $message->to('ivan_dolera@yahoo.com')
                        ->subject('Test Email2');
            });
        
            return 'Test email sent2!';
        });

        Route::get('/send-test-email2', function () {
            Mail::raw('This is a test email from Laravel using GoDaddy SMTP.', function ($message) {
                $message->to('ivandolera24@gmail.com')
                        ->subject('Test Email2');
            });
        
            return 'Test email sent2!';
        });
        */
        /* Dashboard */
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');

        /* Users  */
        Route::get('/users', 'UserController@index')->name('user.show');
        Route::post('/users/create', 'UserController@store');

    });
    
    //Post Data from shopify to laravel db
   
    //Route::post('/auction-post-lara-db-from-shopify', 'AuctionFormSubmitShopifyController@store');
    Route::post('/auction-post-lara-db-from-shopify', 'AuctionsController@AddAuctionToLaravelDB');
    Route::post('/comments-postlaraveldb', 'CommentsController@store');
    Route::post('/bidding-postlaraveldb', 'BiddingController@store');
    

    Route::post('/save-customer-info-from-shopify', 'BiddersController@storeFromShopify');
    Route::post('/save-paymentsdetails', 'BiddersController@storeBankDetailsUsingShopify');
    Route::get('/getCpayments/{acct_id}', 'BiddersController@getCpayments');

    Route::get('/getauctions/{acct_id}/{product_status}', 'ProductsController@getProducts');
    Route::get('/getfontauctions/{type}', 'ProductsController@getFrontAuction');
    Route::get('/update-auctions-to-ended/{product_id}', 'ProductsController@updateAuctionStatusToEnded');
    Route::get('/update-auctions-to-post/{acct_id}/{product_id}', 'ProductsController@updateAuctionStatusToPost');
    Route::get('/update-auctions-to-unfulfilled/{acct_id}/{product_id}', 'ProductsController@updateAuctionStatusToUnfulfilled');
    Route::get('/getauctions-win/{acct_id}/{product_status}', 'ProductsController@getMyWins');
    
    //fulfillment route
    Route::post('/shopify-fullfillment/shipping', 'MyAccountShopifyController@updateShippingTrackingID');
    Route::get('/shopify-fullfillment/getshipping/{product_id}/{acct_id}/{auction_id}', 'MyAccountShopifyController@getShippingTrackingID');
    Route::get('/shopify-fullfillment/getpaymentfile/{product_id}/{acct_id}/{auction_id}', 'MyAccountShopifyController@getPaymentFile');
    Route::post('/shopify-fullfillment/uploadbuyerpaymentfile', 'MyAccountShopifyController@updateShippingPaymentFile');
    Route::get('/shopify-fullfillment/updateconfirmpayment/{product_id}/{acct_id}/{auction_id}', 'MyAccountShopifyController@updateConfirmpayment');
    
    //Route::get('courier-image-upload', [ImageController::class, 'index']);
    //Route::post('courier-image-upload', [ImageController::class, 'store'])->name('image.store');
    //Route::post('/courier-image-upload', 'ImageController@store');
    
    //Negotiations
    Route::post('/negotiationtable/store', 'NegotiationsController@negotation');
    Route::get('/get-neg-by-buyer/{buyer_id}', 'NegotiationsController@getBuyerNegotiation');
    Route::get('/get-neg-by-seller/{seller_id}', 'ProductsController@getNegotiationProductsBySeller');

    // get all product detail like auction, bids, comments
    Route::get('/getproduct/{watch_lot_id}', 'SingleProductDisplayController@getProduct');

   //get favorites
    Route::get('/savefavorites/{auction_id}/{liker_id}', 'FavoritesController@saveFavorites');
    Route::get('/getfavorites/{liker_id}', 'FavoritesController@get');
    


});