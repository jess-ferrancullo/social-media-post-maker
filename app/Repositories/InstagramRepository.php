<?php

namespace App\Repositories;

use App\Models\InstagramApiToken;

class InstagramRepository
{
    public function save(array $requestData)
    {
        return InstagramApiToken::create($requestData);
    }

    public function getApiToken()
    {
        return InstagramApiToken::query()->first();
    }
}
