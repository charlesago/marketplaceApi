<?php

namespace App\Service;

class EncryptorService
{
    private string $secretKey;
    private string $cipherMethod = 'AES-256-CBC';

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function encrypt(string $data): string
    {
        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data, $this->cipherMethod, $this->secretKey, 0, $iv);

        return base64_encode($encrypted . '::' . $iv);
    }

    public function decrypt(string $encryptedData): string
    {
        list($encrypted, $iv) = explode('::', base64_decode($encryptedData), 2);
        return openssl_decrypt($encrypted, $this->cipherMethod, $this->secretKey, 0, $iv);
    }
}