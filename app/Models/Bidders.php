<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Bidders;

class Bidders extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bidders';
    protected $fillable = [
        'acct_id',
        'name',
        'user_name',
        'email',
        'status',
    ];

    public function getBidders(){
        $bidders = Bidders::select('*');
		$bidders = $bidders->where('status','!=','deleted');
        $bidders = $bidders->get();
		return $bidders;
    }

    public function getBidder($acct_id=null){
        $bidder = Bidders::select('*');
		$bidder = $bidder->where('acct_id','=',$acct_id);
        $bidder = $bidder->first();
		return $bidder;
    }

}
