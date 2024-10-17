<?php

namespace App\Controller;

use App\Entity\CreatedApi;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $secret = $_ENV['STRIPE_PUBLISHABLE_KEY'];
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            "STRIPE_PUBLIC_KEY" => $secret
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function addToCart(CreatedApi $createdApi, CartService $cartService): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->addApi($createdApi);
        return $this->redirectToRoute('app_home');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove_row')]
    public function removeRow(CartService $cartService, CreatedApi $createdApi): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->removeRow($createdApi);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/empty', name: 'app_cart_empty')]
    public function emptyCart(CartService $cartService): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $cartService->emptyCart();
        return $this->redirectToRoute('app_cart');
    }
}
