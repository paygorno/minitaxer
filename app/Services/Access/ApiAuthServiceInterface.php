<?php

namespace App\Access;

use App\User;

interface ApiAuthServiceInterface
{
    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    );

    public function login(string $email, string $password): ?string;

    public function logout(User $user);
}