<?php

namespace App\Services;

use GuzzleHttp\Client;

class ShopifyService
{
    protected $client;
    protected $shopUrl;
    protected $accessToken;

    public function __construct()
    {
         //'base_uri' => "https://{$this->shopUrl}/admin/api/2023-01/",
         //'base_uri' => "https://{$this->shopUrl}/admin/api/2024-01/",
        $this->shopUrl = env('SHOPIFY_SHOP_URL');
        $this->accessToken = env('SHOPIFY_ACCESS_TOKEN');
        $this->client = new Client([
            'base_uri' => "https://{$this->shopUrl}/admin/api/2023-01/",
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $this->accessToken,
            ]
        ]);
    }

    public function createProduct(array $productData)
    {
        $response = $this->client->post('products.json', [
            'json' => [
                'product' => $productData
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function updateProduct($productId, array $productData)
    {
        $response = $this->client->put("products/$productId.json", [
            'json' => [
                'product' => $productData
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getAllProductMeta($productId)
    {
        $response = $this->client->request('GET', "products/$productId/metafields.json");

        return json_decode($response->getBody()->getContents(), true);
    }

    public function updateMeta($productId,$metafieldId,$key,$value,$value_type){ // this is not yet working
              
         $response = $this->client->put("products/$productId/metafields/$metafieldId.json", [
            'json' => [
                'metafield' => [
                   // [
                        'namespace' => 'custom',
                        'key' => $key,
                        'value' => $value,
                        //'value_type' => $value_type // Optional

                    //]
                   
                ]
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

    }

    public function getMetaIDByKey($productId, $key){
       
        $response = $this->client->request('GET', "products/$productId/metafields.json");
        $metafields = json_decode($response->getBody()->getContents(), true)['metafields'];
        foreach ($metafields as $metafield) {
            if ($metafield['key'] === $key) {
                return $metafield['id'];
            }
        }

    }


    public function getUsers(){

        $response = $this->client->get('customers.json');
        //return $response->getBody()->getContents();
        return json_decode($response->getBody()->getContents(), true);

    }

    public function getUser($customerid){
        $id = $this->extractCustomerId($customerid);
        //$response = $this->client->get("customers/$customerid/addresses/$customerid.json");
        $response = $this->client->get("customers/$customerid.json");
        //return $response->getBody()->getContents();
        $data = json_decode($response->getBody(), true);

        return $data['customer']['email'] ?? null;
        //return json_decode($response->getBody()->getContents(), true);

    }

    private function extractCustomerId($gid)
    {
        return preg_replace('/[^0-9]/', '', $gid);
    }

    /*
    public function getCustomer()
    {
        // Initialize the Guzzle client
        $client = new Client([
            'base_uri' => 'https://76c14a-0b.myshopify.com',
        ]);

        // Define the API endpoint and the request options
        $endpoint = 'admin/api/2024-01/customers/7519086510124.json';
        $options = [
            'headers' => [
                'X-Shopify-Access-Token' => env('SHOPIFY_ACCESS_TOKEN'),
                'Accept' => 'application/json',
            ],
        ];

        // Make the GET request
        try {
            $response = $client->request('GET', $endpoint, $options);
            $data = json_decode($response->getBody(), true);

            // Return the data or handle it as needed
            return response()->json($data);
        } catch (\Exception $e) {
            // Handle errors (e.g., network issues, invalid API token)
            return response()->json([
                'error' => 'Unable to fetch customer data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    */

    public function getCustomer($customerId)
    {
        $response = $this->client->get("customers/{$customerId}.json");

        return json_decode($response->getBody()->getContents(), true);
    }

    public function checkIfUserLoggedIn($customerId)
    {
        $customer = $this->getCustomer($customerId);

        if ($customer && isset($customer['customer'])) {
            return $customer['customer']['state'] === 'enabled';
        }

        return false;
    }






    /*
    public function getProductBySku($sku){
        
            try {
                $response = $this->client->get('products.json');
                $products = json_decode($response->getBody()->getContents(), true)['products'];

                foreach ($products as $product) {
                    foreach ($product['variants'] as $variant) {
                        if ($variant['sku'] === $sku) {
                            return $product;
                        }
                    }
                }
            } catch (RequestException $e) {
                return null;
            }

            return null;
    }*/
}
