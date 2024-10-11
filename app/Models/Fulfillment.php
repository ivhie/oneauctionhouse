<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Bidders;

class Fulfillment extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fulfillment';
    protected $fillable = [
        'lot_id',
        'buyer_id',
        'acct_id',
    ];

    public function getFulfillments(){

        $fulfillments = Fulfillment::select('*');
		$fulfillments = $fulfillments->where('status','!=','deleted');
        $fulfillments = $fulfillments->get();
		return $fulfillments;



    }

    public function getFulfillment($acct_id=null){
        $fulfillment = Fulfillment::select('*');
		$fulfillment = $fulfillment->where('acct_id','=',$acct_id);
        $fulfillment = $fulfillment->first();
		return $fulfillment;
    }

}
