<?php

namespace App\Services;

use Facebook\Facebook;

class FacebookService
{
    private $facebook;
    // private $pageId = 'me';
    private $pageId = '108227429009976';

    function __construct()
    {
        $this->facebook = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v17.0',
            'default_access_token' => env('FACEBOOK_ACCESS_TOKEN')
        ]);
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

        // dd($postData);
        if ($postData['upload'] === 'image') {
            $imageIds = $this->uploadImages();
            foreach ($imageIds as $index => $id) {
                $params['attached_media'][$index] = '{"media_fbid":"'. $id .'"}';
            }
        }

        $response = $this->facebook->post("/". $this->pageId . "/feed", $params);
        return $response->getDecodedBody();
    }

    public function uploadImages()
    {
        $images = [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/440px-Cat_November_2010-1a.jpg',
            'https://cdn.britannica.com/16/234216-050-C66F8665/beagle-hound-dog.jpg',
            'https://cdn.download.ams.birds.cornell.edu/api/v1/asset/202984001/1800',
            'https://images.immediate.co.uk/production/volatile/sites/23/2022/09/GettyImages-200386624-001-d80a3ec.jpg?quality=90&webp=true&resize=1750,1167',
        ];

        $messages = [
            'cat',
            'dog',
            'bird',
            'fish'
        ];

        $imagePostRequests = [];

        foreach ($images as $index => $image) {
            $params = [
                'url' => $image,
                'message' => $messages[$index],
                'published' => false,
            ];
            $endPoint = "/" . $this->pageId . "/photos";
            $imagePostRequests[] = $this->facebook->request('POST', $endPoint, $params);
        }

        $uploadedImages = $this->facebook->sendBatchRequest($imagePostRequests);
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
            'https://shapeshed.com/examples/HTML5-video-element/video/320x240.ogg'
        ];

        $params = [
            'title' => 'sample video',
            'description' => 'Hello world again',
            'file_url' => $sampleVideoUrls[3],
            // 'file_url' => $sampleVideoUrls[rand(0, 2)],
        ];

        $params = array_merge($params, $requestParams);

        $response = $this->facebook->post("/" . $this->pageId . "/videos", $params);
        // $response = $this->facebook->uploadVideo($this->pageId, $fileUrl, $params);

        return $response;
    }

    public function getFacebookPosts()
    {
        $endPoint = "/" . $this->pageId . "/feed?fields=permalink_url,message,created_time";
        return $this->facebook->get($endPoint)->getDecodedBody();
    }
}
