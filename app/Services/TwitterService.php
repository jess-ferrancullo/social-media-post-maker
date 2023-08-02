<?php

namespace App\Services;

use App\Jobs\TwitterMediaPoster;
use App\Traits\Upload;

class TwitterService
{
    use Upload;

    private $twitterApi;

    function __construct()
    {
        $this->twitterApi = new TwitterAuthService(
            env('TWITTER_CONSUMER_KEY'), 
            env('TWITTER_CONSUMER_SECRET'),
            env('TWITTER_ACCESS_TOKEN'), 
            env('TWITTER_ACCESS_TOKEN_SECRET')
        );
    }

    public function post(array $requestData): mixed
    {
        $params = ['text' => $requestData['text']];
        if (isset($requestData['media'])) {
            foreach ($requestData['media'] as $file) {
                $mime = $file->getmimeType();
                $path = $this->saveImage($file, 'twitter');
                $mediaParam = [
                    'path' => $path,
                    'type' => $mime,
                    'category' => $this->getMediaCategory($mime)
                ];
                $params['media'][] = $mediaParam;
            }

            // calling a job since uploading to twitter takes some time
            TwitterMediaPoster::dispatch($params);
            return true;
        } else {
            return $this->tweet($params);
        }
    }

    public function tweet(array $params): array|object {
        $this->twitterApi->setApiVersion('2');
        return $this->twitterApi->post('tweets', $params, true);
    }
    
    
    private function getMediaCategory(string $mime): string
    {
        $mimeArray = explode('/', $mime);
        $isVideo = $mimeArray[0] === 'video';

        if ($isVideo) {
            return 'tweet_video';
        } else if ($mimeArray[1] === 'gif') {
            return 'tweet_gif';
        } else {
            return 'tweet_image';
        }
    }
}
