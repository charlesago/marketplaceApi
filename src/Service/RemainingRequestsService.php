<?php

namespace App\Service;

use App\Entity\PurchasedApi;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RemainingRequestsService
{
    private HttpClientInterface $httpClient;
    private EncryptorService $encryptorService;

    public function __construct(HttpClientInterface $httpClient, EncryptorService $encryptorService)
    {
        $this->httpClient = $httpClient;
        $this->encryptorService = $encryptorService;
    }

    public function getRemainingRequests(PurchasedApi $purchasedApi)
    {
        $route = $purchasedApi->getLinkApi()->getLinkToApiRequest();

        $route = $route . "/" . $purchasedApi->getLinkToProfile()->getOfUser()->getUuid();

        $response = $this->httpClient->request(
            'GET',
            $route, [
                'headers' => [
                    'API-Key-Plat' => $this->encryptorService->decrypt($purchasedApi->getLinkApi()->getApiKey()),
                ],
            ]
        );

        $content = $response->toArray()["count"];

        return json_decode($content, true);
    }
}