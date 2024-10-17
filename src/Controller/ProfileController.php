<?php

namespace App\Controller;

use App\Entity\PurchasedApi;
use App\Service\EncryptorService;
use App\Service\RemainingRequestsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProfileController extends AbstractController
{
    private $encryptor;

    public function __construct(EncryptorService $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(RemainingRequestsService $service, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $this->getUser();

        $createdApis = $user->getProfile()->getCreatedApis();

        $purchasedApis = $user->getProfile()->getPurchasedApis();

        foreach ($purchasedApis as $api) {
            if ($api->isApiKeyGenerated()) {
                $remainingRequests = $service->getRemainingRequests($api);
                $api->setRemainingRequests($remainingRequests);
                $manager->flush();
            }
        }

        return $this->render('profile/index.html.twig', [
            'createdApis' => $createdApis,
            'purchasedApis' => $purchasedApis,
        ]);
    }

    #[Route('/profile/remove/{id}/purchased-api', name: 'app_profile_remove_purchased_api')]
    public function removePurchasedApi(PurchasedApi $purchasedApi, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $linkApiId = $purchasedApi->getLinkApi()->getId();
        $this->getUser()->getProfile()->removePurchasedApi($purchasedApi);
        $manager->remove($purchasedApi);
        $manager->flush();

        return $this->redirectToRoute('app_create_api_show', ['id' => $linkApiId]);
    }
}
