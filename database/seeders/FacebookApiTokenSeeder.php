<?php

namespace Database\Seeders;

use App\Models\FacebookApiToken;
use Illuminate\Database\Seeder;

class FacebookApiTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'page',
                'user_page_id' => '108227429009976',
                'user_page_name' => 'Test Page for Posting',
                'access_token' => 'EAAKFvXDZBs5UBAMTwos35p5IkuX82qui6eACldW4ZC1gCgFhYjcmjEySGaVL8ISQcZAetjIZA6vIcZBqvLsxZAD3TD9atlMlzvvGYmeBzZA82JQNM9aIeI42IFifH4hqyQ3bWfOC56aTdha8GV3GbZAxZBRwwws6JCVIZAMuOZB1QYAbimj9RdQAvXqd9PuSbpmjRMZD',
                'is_active' => '1',
            ],
            [
                'type' => 'page',
                'user_page_id' => '103597949485499',
                'user_page_name' => 'laravel posts',
                'access_token' => 'EAAKFvXDZBs5UBABk2OZAdClfJqUJ7IDaz6QNzZA35ZASWgCN8TdGYUMti8EfCa1isD5NXl3xxW2IWTuFqoWkrgZCmEb0QKLVKQ85r5VNKcBt5IixgbyxWpYjfdHJviJC8qbCZA5cJyYyVSnZCPDGcGbHAFJOc2WAQyE3u9A7CqkLIhLfuvmEayUmZCXAE6ZB6J5EZD',
                'is_active' => '0',
            ],
            [
                'type' => 'user',
                'user_page_id' => '237473785848065',
                'user_page_name' => 'Facebook Account',
                'access_token' => 'EAAKFvXDZBs5UBABypWEMuYiiyepqaewhglY5gJGFqssfgfFR3mFWCFcrDe4O0I6ERBptyE9hKq2WljZAZAeGRrw4M3vrJNvAGjjtfWhKgYOVe3ra8eCMpdzkSZCE5HhUfnnYZAZC4ZAUnTRCGZAUZAgeWbfephzwn7jCUD772FUc7vgEZAFPFLZBJMy',
                'is_active' => '0',
            ],
        ];

        foreach ($data as $apiToken) {
            FacebookApiToken::create($apiToken);
        }
    }
}
