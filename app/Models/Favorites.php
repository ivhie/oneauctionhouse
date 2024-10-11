<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Bidders;

class Favorites extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorites';
    protected $fillable = [
        'status',
    ];

    public function getFavorites(){

        $favorites = Favorites::select('*');
		$favorites = $favorites->where('status','!=','deleted');
        $favorites = $favorites->get();
		return $favorites;



    }

    public function getNegotiation($auction_id=null){
        $favorite = Favorites::select('*');
		$negotiation = $favorite->where('auction_id','=',$auction_id);
        $favorite = $favorite->first();
		return $favorite;
    }

}
