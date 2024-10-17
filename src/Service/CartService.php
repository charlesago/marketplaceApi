<?php

namespace App\Service;

use App\Entity\CreatedApi;
use App\Repository\CreatedApiRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private SessionInterface $session;
    private CreatedApiRepository $createdApiRepo;

    public function __construct(RequestStack $requestStack, CreatedApiRepository $createdApiRepo)
    {
        $this->session = $requestStack->getSession();
        $this->createdApiRepo = $createdApiRepo;
    }

    public function addApi(CreatedApi $createdApi)
    {
        $cart = $this->session->get('cart', []);
        $cart[$createdApi->getId()] = 1;
        $this->session->set('cart', $cart);
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += $item['createdApi']->getPrice();
        }
        return $total;
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $entityCart = [];

        foreach ($cart as $createdApiId => $quantity) {
            $item = [
                'createdApi' => $this->createdApiRepo->find($createdApiId)
            ];

            $entityCart[] = $item;
        }
        return $entityCart;
    }

    public function removeCreatedApi(CreatedApi $createdApi)
    {
        $cart = $this->session->get('cart', []);
        $createdApiId = $createdApi->getId();

        if (isset($cart[$createdApiId])) {
            $cart[$createdApiId]--;
            if ($cart[$createdApiId] === 0) {
                unset($cart[$createdApiId]);
            }
        }
        $this->session->set('cart', $cart);
    }

    public function emptyCart()
    {
        $this->session->remove('cart');
    }

    public function removeRow(CreatedApi $createdApi)
    {
        $cart = $this->session->get('cart', []);
        $createdApiId = $createdApi->getId();

        if (isset($cart[$createdApiId])) {
            unset($cart[$createdApiId]);
        }
        $this->session->set('cart', $cart);
    }
}