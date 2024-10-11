<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AuctionItems;

class AuctionItems extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auction_items';
    protected $fillable = [
        
        'watch_lot_id',
        'watch_brand',
        'watch_model_number',
        'year_of_watch',
        'watch_package',
        'watch_papers',
        'watch_box',
        'watch_after_market',
        'watch_condition',
        'bidding_date',
        'bidding_time',
        'bidding_title',
        'watchstatus_brand',
        'status',


    ];

    public function LotNumber() {
        $lastid = isset(AuctionItems::latest()->first()->id)?AuctionItems::latest()->first()->id:'';
        if(!$lastid){
            $lastid = 0; //empty
        } else {
            $lastid = $lastid + 1; //increment
        }

        $lastid =  str_pad ( $lastid, 3, "0", STR_PAD_LEFT); // 001,100
        return $lastid;
        
    }

    public function CountAuction($status=null){

        $auctions = AuctionItems::select('*');
        $auctions = $auctions->where('status','!=','deleted');
        $auctions = $auctions->where('product_status','=',$status);
        $auctions_count = $auctions->count();
        return $auctions_count;
    }

}
