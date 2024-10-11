<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Bidders;

class Negotiations extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'negotiations';
    protected $fillable = [
        'status'
    ];

    public function getNegotiations(){

        $negotiations = Negotiations::select('*');
		$negotiations = $negotiations->where('status','!=','deleted');
        $negotiations = $negotiations->get();
		return $negotiations;



    }

    public function getNegotiation($auction_id=null){
        $negotiation = Negotiations::select('*');
		$negotiation = $negotiation->where('auction_id','=',$auction_id);
        $negotiation = $negotiation->first();
		return $negotiation;
    }

}
