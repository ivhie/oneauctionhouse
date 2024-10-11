<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyShopify
{
    public function handle(Request $request, Closure $next)
    {
        // Your verification logic here

        return $next($request);
    }
}

?>