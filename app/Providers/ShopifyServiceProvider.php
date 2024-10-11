<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shopify\Clients\Graphql;

class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //https://chatgpt.com/c/def6d680-5ef5-4ded-a55f-82917af5b9fa
        $this->app->singleton(Graphql::class, function ($app) {
            $shop = config('shopify.shop_url');
            $accessToken = config('shopify.access_token');

           // $shop = config('shopify.shop_url');
           // $accessToken = config('shopify.7bf86dc2bf30bdd99ff2666c99e07195');
            //$shop = config('76c14a-0b.myshopify.com');
            //$shop = config('oneauctionhouse.com');
            //$accessToken = config('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');
           
           //die($shop.'testerss');
            //$accessToken = config('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');

            if (is_null($shop) || is_null($accessToken)) {
                throw new \Exception('Shopify configuration not set correctly.');
            }

            // Debugging statements
            //logger()->info('Shopify Shop URL: ' . $shop);
           // logger()->info('Shopify Access Token: ' . $accessToken);

            return new Graphql($shop, $accessToken);
            /*
            $this->app->singleton(Rest::class, function ($app) {
                return new Rest(
                    config('oneauctionhouse.com'),
                    config('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')
                );
            });
            */


        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
