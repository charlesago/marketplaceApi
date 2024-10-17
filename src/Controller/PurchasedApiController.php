<?php

namespace App\Controller;

use App\Entity\PurchasedApi;
use App\Service\EncryptorService;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PurchasedApiController extends AbstractController
{
    public function __construct(
        private HttpClientInterface    $client,
        private EncryptorService       $encryptorService,
        private EntityManagerInterface $manager,
        private MailService            $mailService
    )
    {
    }

    #[Route('/purchased/api/{id}/generate-new-api-key', name: 'app_purchased_api_generate_new_api_key')]
    public function generateNewApiKey(PurchasedApi $purchasedApi)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $this->deleteApiKey($purchasedApi);
        $this->generateApiKey($purchasedApi);
        return $this->redirectToRoute("app_profile");
    }

    #[Route('/purchased/api/{id}/delete-api-key', name: 'app_purchased_api_delete_api_key')]
    public function deleteApiKey(PurchasedApi $purchasedApi): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $route = $purchasedApi->getLinkApi()->getLinkToApiUserDelete();

        $route = $route . "/" . $purchasedApi->getLinkToProfile()->getOfUser()->getUuid();

        $this->client->request(
            'DELETE',
            $route, [
                'headers' => [
                    'API-Key-Plat' => $this->encryptorService->decrypt($purchasedApi->getLinkApi()->getApiKey()),
                ],
            ]
        );

        $purchasedApi->setApiKeyGenerated(false);
        $this->manager->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/purchased/api/{id}/generate-api-key', name: 'app_purchased_api_generate_api_key')]
    public function generateApiKey(PurchasedApi $purchasedApi): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $apiKey = bin2hex(random_bytes(16));

        if ($this->getUser()->getEmail()) {
            $this->mailService->sendEmail(
                $this->getUser()->getEmail(),
                "Your Api Key DO NOT SHARE !",
                $apiKey
            );
        }


        $apiKey = hash('sha256', $apiKey);

        $clientUrl = $purchasedApi->getLinkApi()->getLinkToApiUser();
        $this->client->request(
            'POST',
            $clientUrl, [
                'headers' => [
                    'API-Key-Plat' => $this->encryptorService->decrypt($purchasedApi->getLinkApi()->getApiKey()),
                ],
                'body' => [
                    'client_id' => $this->getUser()->getId(),
                    'email' => $this->getUser()->getEmail(),
                    'api_key' => $apiKey,
                    'uuid' => $this->getUser()->getUuid(),
                    'count' => $purchasedApi->getRemainingRequests()
                ]
            ]
        );

        $purchasedApi->setApiKeyGenerated(true);
        $this->manager->flush();

        return $this->redirectToRoute('app_profile');
    }

}
