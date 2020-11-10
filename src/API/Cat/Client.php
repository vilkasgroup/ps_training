<?php

namespace Invertus\Training\API\Cat;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

class Client
{
    private const API_URL = 'http://requestkittens.com';

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new GuzzleClient(
            [
                'base_url' => self::API_URL
            ]
        );
    }

    public function getCatFacts()
    {
        $data = [
            'data' => 1
        ];
        $token = '553d327e594a1b5f1b13f198';
        try {
            $response = $this->client->get(
                '/cats',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' .  $token
                    ],
                    'query' => [
                        'emotion' => 'happy'
                    ],
                    'json' => $data
                ]

            );
        } catch (ClientException $e) {
            return $e->getResponse()->getBody()->getContents();
        }

        return $response->getBody()->getContents();
    }
}
