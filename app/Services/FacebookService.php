<?php

namespace App\Services;

use App\Repositories\FacebookRepository;
use App\SingleTons\FacebookApi;
use App\Traits\Upload;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FacebookService
{
    use Upload;

    private $facebook;

    function __construct(private FacebookRepository $facebookRepository)
    {
        $this->facebook = FacebookApi::getInstance()->getApi();
    }

    public function post(array $postData): array
    {
        $params = [];
        if ($postData['upload'] === 'link' && $postData['link']) {
            $params['link'] = $postData['link'];
        }

        if ($postData['upload'] === 'video') {
            $params['description'] = $postData['message'];
            return $this->postVideo($postData['media_video'], $params);
        }

        $params['message'] = $postData['message'];

        if ($postData['upload'] === 'image') {
            $imageIds = $this->uploadImages($postData['media_images']);
            foreach ($imageIds as $index => $id) {
                $params['attached_media'][$index] = '{"media_fbid":"'. $id .'"}';
            }
        }

        $token = $this->facebookRepository->getActiveApiToken();
        $endPoint = "/" . $token->user_page_id . "/feed";
        $response = $this->facebook->post($endPoint, $params);

        return $response->getDecodedBody();
    }

    public function uploadImages(array $images)
    {
        $imagePostRequests = [];
        $token = $this->facebookRepository->getActiveApiToken();
        $endPoint = "/" . $token->user_page_id . "/photos";

        foreach ($images as $index => $image) {
            $path = $this->saveImage($image, 'facebook');
            $params = [
                'url' => Storage::url($path),
                'message' => 'Image # ' . ($index + 1),
                'published' => false,
            ];

            $imagePostRequests[] = $this->facebook->request('POST', $endPoint, $params);
        }

        $uploadedImages = $this->facebook->sendBatchRequest($imagePostRequests);
        $imageIds = [];

        foreach ($uploadedImages as $image) {
            $imageIds[] = $image->getDecodedBody()['id'];
        }

        return $imageIds;
    }


    public function postVideo(UploadedFile $file, array $requestParams)
    {
        $path = $this->saveImage($file, 'facebook');

        $params = [
            'title' => 'sample video',
            'description' => 'Hello world again',
            'file_url' => Storage::url($path),
        ];

        $params = array_merge($params, $requestParams);

        $token = $this->facebookRepository->getActiveApiToken();
        $endPoint = "/" . $token->user_page_id . "/videos";
        $response = $this->facebook->post($endPoint, $params);

        return $response->getDecodedBody();
    }

    public function getFacebookPosts()
    {
        try {
            $token = $this->facebookRepository->getActiveApiToken();
            $this->facebook->setDefaultAccessToken($token->access_token);

            $endPoint = "/" . $token->user_page_id . "/feed?fields=permalink_url,message,created_time";
            $posts = $this->facebook->get($endPoint)->getDecodedBody();
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

        $this->facebook->setDefaultAccessToken($accessToken);
    }

    public function savePageToken(array $requestData)
    {
        $requestData['is_active'] = 0;
        $requestData['type'] = 'page';

        return $this->facebookRepository->savePageToken($requestData);
    }


    // ---  These are helpers to set access tokens ---- //


    public function getUserLongLivedAccessToken()
    {
        $token = $this->facebookRepository->getUserApiToken();
        $params = [
            'grant_type=fb_exchange_token',
            'client_id=' . env('FACEBOOK_APP_ID'),
            'client_secret=' . env('FACEBOOK_APP_SECRET'),
            'fb_exchange_token=' . $token->access_token,
        ];

        $endPoint = 'oauth/access_token?' . implode('&', $params);
        $response = $this->facebook->get($endPoint);

        $user = $response->getDecodedBody();
        $this->facebookRepository->updateAccessToken($token->user_page_id, $user['access_token']);

        return $user['access_token'];
    }

    public function getLongLivedPageAccessToken()
    {
        $userToken = $this->facebookRepository->getUserApiToken();
        $endPoint = $userToken->user_page_id . '/accounts?access_token=' . $userToken->access_token;

        $this->facebook->setDefaultAccessToken($userToken->access_token);
        $response = $this->facebook->get($endPoint);
        
        $data = $response->getDecodedBody();

        foreach ($data['data'] as $token) {
            $this->facebookRepository->updateAccessToken($token['id'], $token['access_token']);
        }
    }

}
