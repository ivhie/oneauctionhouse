<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Kyon147\LaraShopify\LaraShopify;

class ShopifyController extends Controller
{
    public function install()
    {
        $shop = request('shop');
        $shopify = new LaraShopify($shop);
        return $shopify->redirect();
    }

    public function callback()
    {
        $shopify = new LaraShopify(request('shop'));
        $accessToken = $shopify->getAccessToken(request('code'));

        // Store the access token and shop information
        // For example, save it to the database or session
        // Your code here...

        return redirect()->route('home');
    }

    public function getProducts()
    {
        $accessToken = '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'; // Replace with your actual access token
        $shopifyStore = '76c14a-0b.myshopify.com'; // Replace with your Shopify store URL
        $apiVersion = '2024-07'; // Replace with the API version you are targeting

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://$shopifyStore/admin/api/$apiVersion/products.json");

        // Check if request was successful (status code 200)
        if ($response->successful()) {
            $products = $response->json()['products'];
            // Process $products as needed
            return response()->json($products);
        } else {
            // Handle the error response
            $errorCode = $response->status();
            $errorMessage = $response->json()['errors']; // Example of extracting error message
            return response()->json(['error' => "Error $errorCode: $errorMessage"], $errorCode);
        }
    }
}