<?php

namespace App\Repositories\Contracts;

interface EmailVerificationRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values);

    public function findByEmail(string $email);

    public function findByOtp(string $otp);
}
