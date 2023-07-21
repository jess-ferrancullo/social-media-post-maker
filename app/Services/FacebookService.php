<?php

namespace App\Services;

use App\Repositories\FacebookRepository;
use App\SingleTons\FacebookApi;
use Exception;

class FacebookService
{
    private $facebook;

    function __construct(private FacebookRepository $facebookRepository)
    {
        $this->facebook = FacebookApi::getInstance();
    }

    public function post(array $postData)
    {
        $params = [];
        if ($postData['link']) {
            $params['link'] = $postData['link'];
        }
        if ($postData['upload'] === 'video') {
            $params['description'] = $postData['message'];
            return $this->postVideo($params);
        }

        $params['message'] = $postData['message'];

        if ($postData['upload'] === 'image') {
            $imageIds = $this->uploadImages();
            foreach ($imageIds as $index => $id) {
                $params['attached_media'][$index] = '{"media_fbid":"'. $id .'"}';
            }
        }

        $token = $this->facebookRepository->getActiveApiToken();
        $endPoint = "/" . $token->user_page_id . "/feed";
        $response = $this->facebook->getApi()->post($endPoint, $params);

        return $response->getDecodedBody();
    }

    public function uploadImages()
    {
        $images = [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/440px-Cat_November_2010-1a.jpg',
            'https://cdn.britannica.com/16/234216-050-C66F8665/beagle-hound-dog.jpg',
            'https://cdn.download.ams.birds.cornell.edu/api/v1/asset/202984001/1800',
            'https://images.immediate.co.uk/production/volatile/sites/23/2022/09/GettyImages-200386624-001-d80a3ec.jpg?quality=90&webp=true&resize=1750,1167',
            'https://cdn.stg.the-3rd.io/post_images/41/posts/396/LKHE0IRHPA3i6SCtK19qGAWWQGFxIHU22wosRrtL.jpg'
        ];

        $messages = [
            'cat',
            'dog',
            'bird',
            'fish',
            'barbeque'
        ];

        $imagePostRequests = [];
        $token = $this->facebookRepository->getActiveApiToken();
        $endPoint = "/" . $token->user_page_id . "/photos";

        foreach ($images as $index => $image) {
            $params = [
                'url' => $image,
                'message' => $messages[$index],
                'published' => false,
            ];
            $imagePostRequests[] = $this->facebook->getApi()->request('POST', $endPoint, $params);
        }

        $uploadedImages = $this->facebook->getApi()->sendBatchRequest($imagePostRequests);
        $imageIds = [];

        foreach ($uploadedImages as $image) {
            $imageIds[] = $image->getDecodedBody()['id'];
        }

        return $imageIds;
    }

    public function postVideo(array $requestParams)
    {
        $sampleVideoUrls = [
            'https://download-video.akamaized.net/2/playback/5578dc59-b11b-4408-93a8-8ad9a6b25d03/a4897bbb-8158b433?__token__=st=1689749023~exp=1689763423~acl=%2F2%2Fplayback%2F5578dc59-b11b-4408-93a8-8ad9a6b25d03%2Fa4897bbb-8158b433%2A~hmac=c310e5649e4ddca2c4622e3d3f0b454c5c3b0a2b7c48113d0482c9dbea37dc78&r=dXMtd2VzdDE%3D',
            'https://www.w3schools.com/html/mov_bbb.mp4',
            'https://media.w3.org/2010/05/sintel/trailer.mp4',
            'https://shapeshed.com/examples/HTML5-video-element/video/320x240.ogg',
            
        ];

        $params = [
            'title' => 'sample video',
            'description' => 'Hello world again',
            'file_url' => array_rand($sampleVideoUrls),
        ];

        $params = array_merge($params, $requestParams);

        $token = $this->facebookRepository->getActiveApiToken();
        $endPoint = "/" . $token->user_page_id . "/videos";
        $response = $this->facebook->getApi()->post($endPoint, $params);
        // $response = $this->facebook->uploadVideo($this->pageId, $fileUrl, $params);

        return $response;
    }

    public function getFacebookPosts()
    {
        return [];
        try {
            $token = $this->facebookRepository->getActiveApiToken();
            $endPoint = "/" . $token->user_page_id . "/feed?fields=permalink_url,message,created_time";
            $posts = $this->facebook
                ->getApi()
                ->get($endPoint)
                ->getDecodedBody();
        } catch (Exception $e) {
            return [];
        }

        return $posts['data'];
    }

    public function getFacebookPages()
    {
        return $this->facebookRepository->getPages();
    }
    
    public function setActivePage(string $pageId)
    {
        $this->facebookRepository->setActivePage($pageId);
        $accessToken = $this->facebookRepository->getActiveApiToken()->access_token;

        $this->facebook->setAccessToken($accessToken);
    }

    public function savePageToken(array $requestData)
    {
        $requestData['is_active'] = 0;
        $requestData['type'] = 'page';

        return $this->facebookRepository->savePageToken($requestData);
    }
}
