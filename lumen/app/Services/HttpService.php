<?php

namespace App\Services;

use GuzzleHttp\Client;

class HttpService
{
    public static function request($method, $url, $options = [])
    {
        $guzzle = new Client();
        $response = $guzzle->request($method, $url, $options);
        if ($response->getStatusCode() !== 200) {
            return false;
        }
        $responseBody = (object) json_decode($response->getBody(), true);
        return $responseBody;
    }
}
