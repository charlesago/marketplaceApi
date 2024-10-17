<?php

namespace App\Controller;

use App\Entity\CreatedApi;
use App\Entity\PurchasedApi;
use App\Form\CreateApiType;
use App\Repository\PurchasedApiRepository;
use App\Service\EncryptorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class CreateApiController extends AbstractController
{
    private $encryptor;

    public function __construct(EncryptorService $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    #[Route('/create/api', name: 'app_create_api')]
    #[Route('/edit/{id}/api', name: 'app_edit_api')]
    public function create(Request $request, EntityManagerInterface $manager, CreatedApi $createdApi = null): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $edit = false;
        if ($createdApi) {
            $edit = true;
        }
        if (!$edit) {
            $createdApi = new CreatedApi();
        }
        $form = $this->createForm(CreateApiType::class, $createdApi, [
            'editKey' => $edit
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$edit) {
                $createdApi->setApiKey($this->encryptor->encrypt($createdApi->getApiKey()));
            }
            $createdApi->setCreator($this->getUser()->getProfile());
            $manager->persist($createdApi);
            $manager->flush();
            return $this->redirectToRoute('app_profile');
        }
        return $this->render('create_api/create.html.twig', [
            "createApiForm" => $form->createView(),
            "edit" => $edit
        ]);
    }

    #[Route('/delete/{id}/api', name: 'app_delete_api')]
    public function delete(CreatedApi $createdApi, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $isDeleted = false;

        if ($createdApi->getPurchasedApis()->count() == 0) {
            $manager->remove($createdApi);
            $manager->flush();
            $isDeleted = true;
        }


        return $this->redirectToRoute('app_profile', ['isDeleted' => $isDeleted]);
    }


    #[Route('/show/{id}', name: 'app_create_api_show')]
    public function show(CreatedApi $createdApi)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('create_api/show.html.twig', [
            'createdApi' => $createdApi,
        ]);
    }
}