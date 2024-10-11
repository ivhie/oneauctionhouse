<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Osiset\BasicShopifyAPI\BasicShopifyAPI;
use Osiset\BasicShopifyAPI\Store\Memory;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

       // $user = auth()->user();
        if (! $request->expectsJson()) {
            return route('login.show');
            //echo redirect('/');
           
        } //else {
           // return redirect('/home/dashboard');
      //  }
     // $user = auth()->user();
      //if ($user) {
       //     return redirect('/');
        
      //  }

        
        
    }
}
