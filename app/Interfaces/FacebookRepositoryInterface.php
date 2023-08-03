<?php

namespace App\Repositories;

use App\Models\FacebookApiToken;
use Illuminate\Database\Eloquent\Collection;

interface FacebookRepositoryInterface
{
    public function getPages(): Collection;

    public function getUser(): FacebookApiToken;

    public function getActiveApiToken(): FacebookApiToken;

    public function getUserApiToken(): FacebookApiToken;

    public function updateAccessToken(string $userPageId, string $accessToken): void;
    
    public function getAccessTokenByPage(string $userPageId): ?FacebookApiToken;

    public function setActivePage(string $pageId): void;

    public function savePageToken(array $requestData);
}