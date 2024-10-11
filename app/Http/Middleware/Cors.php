<?php

// app/Http/Middleware/Cors.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        /*return $next($request)
            ->header('Access-Control-Allow-Origin', '*') // Replace '*' with the allowed domain(s)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin');*/

            $response = $next($request);

            $response->headers->set('Access-Control-Allow-Origin' , 'https://oneauctionhouse.com');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

            return $response;
    }
}

