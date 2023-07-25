<?php

namespace Database\Seeders;

use App\Models\InstagramApiToken;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstagramApiTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'instagram_business_account' => '17841460989493179',
            'facebook_page_id' => '103597949485499',
            'access_token' => 'EAAKFvXDZBs5UBABk2OZAdClfJqUJ7IDaz6QNzZA35ZASWgCN8TdGYUMti8EfCa1isD5NXl3xxW2IWTuFqoWkrgZCmEb0QKLVKQ85r5VNKcBt5IixgbyxWpYjfdHJviJC8qbCZA5cJyYyVSnZCPDGcGbHAFJOc2WAQyE3u9A7CqkLIhLfuvmEayUmZCXAE6ZB6J5EZD',
        ];

        InstagramApiToken::create($data);
    }
}
