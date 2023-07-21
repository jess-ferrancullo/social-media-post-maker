<?php

namespace App\Repositories;

use App\Models\FacebookApiToken;
use Illuminate\Database\Eloquent\Collection;

class FacebookRepository
{
    public function getPages(): Collection
    {
        return FacebookApiToken::where('type', 'page')->get();
    }

    public function getUser(): FacebookApiToken
    {
        return FacebookApiToken::where('type', 'user')->first();
    }

    public function getActiveApiToken(): FacebookApiToken
    {
        return FacebookApiToken::where('is_active', '1')->first();
    }

    public function setActivePage(string $pageId): void
    {
        FacebookApiToken::where('is_active', '1')->update(['is_active' => '0']);
        FacebookApiToken::where('user_page_id', $pageId)->update(['is_active' => '1']);
    }

    public function savePageToken(array $requestData)
    {
        return FacebookApiToken::create($requestData);
    }
}
