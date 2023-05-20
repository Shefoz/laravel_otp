<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function create(array $data);

    public function findById($id);

    public function findByEmail(string $email);

    public function update($id, array $data);
}
