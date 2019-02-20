<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class RegisterOrderCreateShopifyWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $domain;

    /**
     * @var string
     */
    public $token;

    /**
     * @var \App\Store
     */
    public $store;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain, $token, \App\Store $store)
    {
        $this->domain = $domain;
        $this->token = $token;
        $this->store = $store;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $shopify = \BNMetrics\Shopify\Facade\ShopifyFacade::retrieve($this->domain, $this->token);

        // Get the current uninstall webhooks
        $orderCreateWebhook = array_get($shopify->get('webhooks', [
            'topic' => 'orders/create',
            'limit' => 250,
            'fields' => 'id,address'
        ]), 'webhooks', []);

        // Check if the uninstall webhook has already been registered
        if(collect($orderCreateWebhook)->isEmpty()) {
            $shopify->create('webhooks', [
                'webhook' => [
                    'topic' => 'orders/create',
                    'address' => env('APP_URL') . "webhook/shopify/order-created",
                    'format' => 'json'
                ]
            ]);
        }
        Log::info($this->domain);
        Log::info($this->token);
        Log::info($orderCreateWebhook);
    }
}
