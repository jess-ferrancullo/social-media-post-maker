<?php

namespace App\Jobs;

use App\Services\TwitterAuthService;
use App\Services\TwitterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TwitterMediaPoster implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 60;
    public $timeout = 500;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $twitterApi = new TwitterAuthService(
            env('TWITTER_CONSUMER_KEY'), 
            env('TWITTER_CONSUMER_SECRET'),
            env('TWITTER_ACCESS_TOKEN'), 
            env('TWITTER_ACCESS_TOKEN_SECRET')
        );
        $twitterApi->setApiVersion('1.1');

        $mediaIds = [];
        foreach ($this->data['media'] as $media) {
            $mediaParam = [
                'media' => $media['path'],
                'media_type' => $media['type'],
                'media_category' => $media['category']
            ];

            if ($media['category'] === 'tweet_video'|| $media['category'] === 'tweet_gif' ) {
                $uploaded = $twitterApi->upload('media/upload', $mediaParam, true);
                $mediaIds['media_ids'][] = $uploaded->media_id_string;
                Log::info("response data: ", (array)$uploaded);
            } else {
                $twitterApi->setDecodeJsonAsArray(true);
                $uploaded = $twitterApi->upload('media/upload', $mediaParam);
                $mediaIds['media_ids'][] = $uploaded['media_id_string'];
                Log::info("response data: ", $uploaded);
            }
        }

        $params = ['text' => $this->data['text']];
        if (sizeof($mediaIds)) {
            $params['media'] = $mediaIds;
        }
        
        app()->make(TwitterService::class)->tweet($params);
    }
}
