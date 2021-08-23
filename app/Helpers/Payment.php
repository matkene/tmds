<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Payment
{
    public static function authenticate()
    {
        $client = new Client();
        $url = config('payment.base_url');

        $response = $client->post($url . '/mda-integration/get-api-key', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                "username" =>  "dev_culture_tourism_mda",
                "password" =>  "dev@culture_and_tourism_ministry#2021",
                "generate_new_key"  => false,
            ]
        ]);

        return json_decode($response->getBody());
    }
}
