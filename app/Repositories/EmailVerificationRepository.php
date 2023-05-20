<?php

namespace App\Repositories;

use App\Models\EmailVerification;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;

class EmailVerificationRepository implements EmailVerificationRepositoryInterface
{
    public function updateOrCreate(array $attributes, array $values)
    {
        return EmailVerification::updateOrCreate($attributes, $values);
    }

    public function findByEmail(string $email)
    {
        return EmailVerification::where('email', $email)->first();
    }

    public function findByOtp(string $otp)
    {
        return EmailVerification::where('otp', $otp)->first();
    }
}
