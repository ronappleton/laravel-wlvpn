<?php

namespace RonAppleton\WLVPN\Services;

use GuzzleHttp\Client;

class VPN
{
    private $client;
    private $endpoint = 'https://api.wlvpn.com';

    public function __construct()
    {
        $this->client = new Client(
            [
                'auth' => [
                    'api-key',
                    env('WLVPN_API_KEY', '')
                ]
            ]
        );
    }

    public function CreateAccount(array $user)
    {

    }

    public function UpdateAccount(array $user)
    {

    }

    public function UsageStatistics(array $user)
    {

    }


}
