<?php

namespace App\Jobs;

use App\Services\InstagramService;
use App\SingleTons\FacebookApi;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Log;
use Throwable;

class InstagramMediaPublishedChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $timeout = 120;
    public $tries = 10;
    public $backoff = 60;
    // public $instagramApi;

    private const MEDIA_IN_PROGRESS = 'IN_PROGRESS';
    private const MEDIA_FINISHED = 'FINISHED';

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
        $instagramApi = FacebookApi::getInstance()->getApi();
        $instagramApi->setDefaultAccessToken($this->data['access_token']);

        Log::info('Number of tries : ' . $this->attempts());

        foreach ($this->data['container_ids'] as $containerId) {
            $endpoint = $containerId . '?fields=status_code';
            $response = $instagramApi->get($endpoint);
            // dd($response);
            $status = $response->getDecodedBody()['status_code'];

            $finishedUploading = ($status == self::MEDIA_FINISHED);
            Log::info("Status of media : " . $status . PHP_EOL . json_encode($response->getDecodedBody()));

            if (!$finishedUploading) {
                throw new Exception('Media is still in progress, we cannot publish the post yet');
            }
        }

        Log::info('All medias are finished publishing. We can now publish the IG post...');
        app()->make(InstagramService::class)->publishToInstagram($this->data);
    }

}

