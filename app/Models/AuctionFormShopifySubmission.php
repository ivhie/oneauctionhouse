<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionFormShopifySubmission extends Model
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


}
