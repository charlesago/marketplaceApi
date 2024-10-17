<?php

namespace App\Service;

class PaymentService
{
    private $stripeSecretKey;
    public function __construct($stripeSecretKey = null)
    {
        $this->stripeSecretKey = getenv('STRIPE_SECRET_KEY');
    }
}