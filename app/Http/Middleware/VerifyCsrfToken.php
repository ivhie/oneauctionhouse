<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auction-post-lara-db-from-shopify',
        'comments-postlaraveldb',
        'bidding-postlaraveldb',
        'getauctions',
        'save-customer-info-from-shopify',
        'update-auctions-to-ended',
        'courier-image-upload',
        'shopify-fullfillment/*',
        'save-paymentsdetails',
        'getCpayments/*',
        'negotiationtable/*',
        'savefavorites/*',
        'getfavorites/*',
        'get-neg-by-buyer',
    ];
}
