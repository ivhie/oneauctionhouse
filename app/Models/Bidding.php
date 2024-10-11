<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bidding';
    protected $fillable = [
        'acct_id',
        'lot_id',
        'bid_amt',
        'status',
    ];
}
