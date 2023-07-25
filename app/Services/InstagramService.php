<?php

namespace App\Services;

use App\Jobs\InstagramMediaPublishedChecker;
use App\Repositories\FacebookRepository;
use App\Repositories\InstagramRepository;
use App\SingleTons\FacebookApi;
use Exception;

class InstagramService
{
    private $instagram;
    // private $pageId = 'me';
    private $pageId = '108227429009976';

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

    public function post(array $requestData)
    {
        # code...
        // dd($requestData);
        $apiToken = $this->instagramRepository->getApiToken();
        // $endpoint = '18001467613820256' . '?fields=status_code';
        //     $response = $this->instagram->get($endpoint);
        //     dd($response);

        $responses = [];
        if ($requestData['post_type'] === self::POST_TYPE_CAROUSEL) {
            $responses[] = $this->postSampleData($requestData, '1st picture in carousel', 0);
            $responses[] = $this->postSampleData($requestData, '2nd picture in carousel', 1);
            $responses[] = $this->postSampleData($requestData, '3rd picture in carousel', 2);
        } else {
            $responses[] = $this->postSampleData($requestData);
        }

        $payload = [
            'instagram_business_account' => $apiToken->instagram_business_account,
            'access_token' => $apiToken->access_token,
            'post_type' => $requestData['post_type'],
            'caption' => $requestData['caption'],
            'container_ids' => array_column($responses, 'id'),
        ];

        try {
            InstagramMediaPublishedChecker::dispatch($payload)->delay(30);
            // InstagramMediaPublishedChecker::dispatch($payload)->delay(30);
        } catch(Exception $e) {
            echo '<pre>';
            print_r($payload);
            print_r($responses);
            print_r($e);
            die();
        }
    }

    public function postSampleData($requestData, $mediaCaption = '', $index = null) {
    // public function postSampleData($postType, $fileType, $caption = '') {
        $apiToken = $this->instagramRepository->getApiToken();

        $sampleImages = [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/440px-Cat_November_2010-1a.jpg',
            'https://cdn.britannica.com/16/234216-050-C66F8665/beagle-hound-dog.jpg',
            'https://cdn.stg.the-3rd.io/post_images/41/posts/396/LKHE0IRHPA3i6SCtK19qGAWWQGFxIHU22wosRrtL.jpg'
        ];

        $sampleVideos= [
            // 'https://download-video.akamaized.net/2/playback/5578dc59-b11b-4408-93a8-8ad9a6b25d03/a4897bbb-8158b433?__token__=st=1689749023~exp=1689763423~acl=%2F2%2Fplayback%2F5578dc59-b11b-4408-93a8-8ad9a6b25d03%2Fa4897bbb-8158b433%2A~hmac=c310e5649e4ddca2c4622e3d3f0b454c5c3b0a2b7c48113d0482c9dbea37dc78&r=dXMtd2VzdDE%3D',
            // 'https://www.w3schools.com/html/mov_bbb.mp4',
            // 'https://media.w3.org/2010/05/sintel/trailer.mp4',
            // 'https://shapeshed.com/examples/HTML5-video-element/video/320x240.ogg',
            'https://samplelib.com/lib/preview/mp4/sample-10s.mp4'
        ];

        $params = ['caption' => $requestData['caption']];
        if ($requestData['file_type'] === self::FILE_TYPE_IMAGE) {
            $selected = $index ? $sampleImages[$index] : array_rand(array_flip($sampleImages));
            $params['image_url'] = $selected;
        } else {
            $params['video_url'] = array_rand(array_flip($sampleVideos));
            $params['media_type'] = self::FILE_TYPE_VIDEO;
        }
        if ($requestData['post_type'] === self::POST_TYPE_REEL || $requestData['post_type'] === self::POST_TYPE_STORY) {
            $params['media_type'] = $requestData['post_type'];
        } else if ($requestData['post_type'] === self::POST_TYPE_CAROUSEL) {
            $params['is_carousel_item'] = true;
            $params['caption'] = $mediaCaption;
        }

        // dd($params);
        $this->instagram->setDefaultAccessToken($apiToken->access_token);

        $endPoint = '/' . $apiToken->instagram_business_account . '/media';
        $response = $this->instagram->post($endPoint, $params);

        return $response->getDecodedBody();
    }


    public function publishToInstagram(array $mediaPayload)
    {
        if ($mediaPayload['post_type'] !== self::POST_TYPE_CAROUSEL) {
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
            'media_type' => 'carousel',
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
        return [];
        $apiToken = $this->instagramRepository->getApiToken();
        $this->instagram->setDefaultAccessToken($apiToken->access_token);

        $endPoint = $apiToken->instagram_business_account . '/media';
        $response = $this->instagram->get($endPoint);

        return $response->getDecodedBody()['data'];
    }
}
