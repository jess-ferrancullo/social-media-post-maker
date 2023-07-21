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
                'access_token' => 'EAAKFvXDZBs5UBALyVXWZBFiPdx2K3drQyZB8jhJy91ZCYevXXhwZCGj9RHuCEmjRt5ZClfKlq6A8uaVmumNyavD2ZCiMvLZCDJqqZBefJV9UA8TcTebEpur2XyzanY0NfdHk28KI1Ooin339sKQDcbkOB5oLm5vPuk3rzVBZBH5qZARFYP3zMw5UPCxkC3rZCo55yksmT767pdsL9ZAlFp6qtqzG9',
                'is_active' => '1',
            ],
            [
                'type' => 'page',
                'user_page_id' => '103597949485499',
                'user_page_name' => 'laravel posts',
                'access_token' => 'EAAKFvXDZBs5UBAEaD6kijl6SMGN0vkWPHXfM8AjPG93r4m5ZAJRDwAwZBA0JBkxvoe614JNsjnUsHTWcMzSN3KDIqk4nKm4iXWdWurOSkZBZChniMo2aeaKfnYqrZC7VJ932ZAZC5rOOJGSDyDMk2Ry4tuvZC528HHduloIZBWRUsrEtaS6yeR1S9GzXVHZAPvSMPMKZAJb9Pk5P2WDp8szzHum7',
                'is_active' => '0',
            ],
            [
                'type' => 'user',
                'user_page_id' => '237473785848065',
                'user_page_name' => 'Facebook Account',
                'access_token' => 'EAAKFvXDZBs5UBAIHN9CzaaRVxycrZBaUd1rZALxMJ8uhKGe3ZCuoxSuL4BwoGZAGfJce1ohZBxxkX1XSMLOOIWKtuxfZBsW9YdZCycKOhLsAYV1lmZCzuHmYIavQdfXVZBG8vRcGCJaX1cQZAkuw3U3YzvaO8WWoU3kECmxZB4yioACTP0HhnF2UIYGgKZChuF4DPosuERCwlGNToLCaPWJaAAGwhuaZB3o20kU2KDFl5Jm40YSbHFt6iZC1qnqtdWlU6obxlsZD',
                'is_active' => '0',
            ],
        ];

        foreach ($data as $apiToken) {
            FacebookApiToken::create($apiToken);
        }
    }
}
