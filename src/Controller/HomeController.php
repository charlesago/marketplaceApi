<?php

namespace App\Controller;

use App\Repository\CreatedApiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CreatedApiRepository $createdApiRepository): Response
    {
        $availableApis = $createdApiRepository->findAll();
        return $this->render('home/index.html.twig', [
            'apis' => $availableApis,
        ]);
    }
}
