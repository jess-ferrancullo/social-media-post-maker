<?php

namespace App\Repositories;

use App\Models\InstagramApiToken;

interface InstagramRepositoryInterface
{
    public function save(array $requestData): bool;

    public function getApiToken(): ?InstagramApiToken;
}