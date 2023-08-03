<?php

namespace App\Repositories;

use App\Models\InstagramApiToken;

class InstagramRepository
{
    public function save(array $requestData): bool
    {
        return InstagramApiToken::create($requestData);
    }

    public function getApiToken(): ?InstagramApiToken
    {
        return InstagramApiToken::query()->first();
    }
}
