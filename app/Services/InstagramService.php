<?php

namespace App\Services;

use App\Jobs\InstagramMediaPublishedChecker;
use App\Repositories\FacebookRepository;
use App\Repositories\InstagramRepository;
use App\SingleTons\FacebookApi;
use App\Traits\Upload;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstagramService
{
    use Upload;

    private $instagram;

    private const POST_TYPE_FEED = 'FEED';
    private const POST_TYPE_STORY = 'STORIES';
    private const POST_TYPE_REEL = 'REELS';
    private const POST_TYPE_CAROUSEL = 'CAROUSEL';

    private const FILE_TYPE_IMAGE = 'IMAGE';
    private const FILE_TYPE_VIDEO = 'VIDEO';

    function __construct(
        private FacebookRepository $facebookRepository,
        private InstagramRepository $instagramRepository
    )
    {
        $this->instagram = FacebookApi::getInstance()->getApi();
    }

    public function post(array $requestData): void
    {
        # code...
        $apiToken = $this->instagramRepository->getApiToken();

        $caption = $requestData['caption'] ?? '';
        $isStory = isset($requestData['post_type']);
        $isCarousel = (count($requestData['uploads'])) && !$isStory;
        
        $responses = [];
        foreach ($requestData['uploads'] as $file) {
            $filePath = $this->saveImage($file, 'instagram');
            $responses[] = $this->uploadMedia($filePath, $caption, $isCarousel, $isStory);
        }

        $payload = [
            'instagram_business_account' => $apiToken->instagram_business_account,
            'access_token' => $apiToken->access_token,
            'is_carousel' => $isCarousel,
            'caption' => $caption,
        ];

        try {
            if ($isStory) {
                foreach($responses as $response) {
                    $payload['container_ids'] = [$response['id']];
                    InstagramMediaPublishedChecker::dispatch($payload)->delay(10);
                }
            } else {
                $payload['container_ids'] = array_column($responses, 'id');
                InstagramMediaPublishedChecker::dispatch($payload)->delay(30);
            }
        } catch(Exception $e) {
            echo '<pre>';
            print_r($payload);
            print_r($responses);
            print_r($e);
            die();
        }
    }

    public function uploadMedia(
        string $path, 
        string $caption, 
        $isCarousel = false,
        $isStory = false, 
    ): array {
        $fileUrl = Storage::url($path);
        $mime = Storage::mimeType($path);

        $params = ['caption' => $caption];
        if (explode('/', $mime)[0] === 'image') {
            $params['image_url'] = $fileUrl;
        } else {
            $params['video_url'] = $fileUrl;
            $params['media_type'] = $isCarousel ? self::FILE_TYPE_VIDEO : self::POST_TYPE_REEL;
        }

        if ($isStory) {
            $params['media_type'] = self::POST_TYPE_STORY;
        } else if ($isCarousel) {
            $params['is_carousel_item'] = true;
            $params['caption'] = 'carousel item';
        }

        $apiToken = $this->instagramRepository->getApiToken();
        $this->instagram->setDefaultAccessToken($apiToken->access_token);

        $endPoint = '/' . $apiToken->instagram_business_account . '/media';
        $response = $this->instagram->post($endPoint, $params);

        Log::info('Upload Request: ', $params);
        Log::info('Upload Response: ', $response->getDecodedBody());

        return $response->getDecodedBody();
    }

    public function publishToInstagram(array $mediaPayload)
    {
        if (!$mediaPayload['is_carousel']) {
            return $this->publishPost($mediaPayload);
        } 

        return $this->publishCarousel($mediaPayload);
    }

    public function publishPost(array $params) {
        $endPoint =  '/' . $params['instagram_business_account'] . '/media_publish';
        $params = ['creation_id' =>  $params['container_ids'][0]];

        $response = $this->instagram->post($endPoint, $params);

        return $response;
    }

    public function publishCarousel(array $params)
    {
        $endPoint = '/' . $params['instagram_business_account'] . '/media';
        $params = [
            'media_type' => 'CAROUSEL',
            'caption' => $params['caption'],
            'children' => $params['container_ids'],
        ];
        
        $carouselRequest = $this->instagram->post($endPoint, $params);

        $endPoint .= '_publish';
        $params['creation_id'] = $carouselRequest->getDecodedBody()['id'];

        $response = $this->instagram->post($endPoint, $params);

        return $response;
    }

    public function connectFacebookToInstagram(string $facebookPageId)
    {
        $facebookPage = $this->facebookRepository->getAccessTokenByPage($facebookPageId);

        $endPoint = $facebookPage->user_page_id . "?fields=instagram_business_account";
        $this->instagram->setDefaultAccessToken($facebookPage->access_token);

        $response = $this->instagram->get($endPoint, $facebookPage->access_token);
        $response = $response->getDecodedBody();

        $data = [
            'instagram_business_account' => $response['instagram_business_account']['id'],
            'facebook_page_id' => $response['id'],
            'access_token' => $facebookPage->access_token,
        ];

        $this->instagramRepository->save($data);
    }

    public function getInstagramPosts()
    {
        $params = [
            'caption',
            'media_type',
            'media_product_type',
            'media_url',
            'thumbnail_url',
            'permalink',
            'children',
            
        ];
        $carouselParams = [
            'media_type',
            'media_url',
        ];

        $carouselParams = implode(',', $carouselParams);
        $fields = implode(",", $params);
        $fields .= '{' . $carouselParams . '}';

        $apiToken = $this->instagramRepository->getApiToken();
        $this->instagram->setDefaultAccessToken($apiToken->access_token);

        $endPoint = $apiToken->instagram_business_account . '/media?fields=' . $fields;
        $response = $this->instagram->get($endPoint);

        return $response->getDecodedBody()['data'];
    }
}
