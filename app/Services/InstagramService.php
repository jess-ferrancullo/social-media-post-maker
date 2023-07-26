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
            'https://samplelib.com/lib/preview/mp4/sample-10s.mp4',
            'https://im.ezgif.com/tmp/ezgif-1-67cf8f176f.mp4',
            'https://im.ezgif.com/tmp/ezgif-1-b65f26822a.mp4',
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

    // Data came from insta API. This is made so that were not gonna make too much requests
    public function getInstagramPostsDummy() 
    {
        return unserialize('a:6:{i:0;a:7:{s:7:"caption";s:21:"Will this video post?";s:10:"media_type";s:5:"VIDEO";s:18:"media_product_type";s:5:"REELS";s:9:"media_url";s:572:"https://scontent.cdninstagram.com/o1/v/t16/f1/m82/954F4919AAA6CC2ABFA16F38389782AA_video_dashinit.mp4?efg=eyJ2ZW5jb2RlX3RhZyI6InZ0c192b2RfdXJsZ2VuLjEyODAuY2xpcHMifQ&_nc_ht=scontent.cdninstagram.com&_nc_cat=107&vs=293224540033397_3130578622&_nc_vs=HBkcFQIYT2lnX3hwdl9yZWVsc19wZXJtYW5lbnRfcHJvZC85NTRGNDkxOUFBQTZDQzJBQkZBMTZGMzgzODk3ODJBQV92aWRlb19kYXNoaW5pdC5tcDQVAALIAQAoABgAGwGIB3VzZV9vaWwBMRUAACb0qrXGupz%2BPxUCKAJDMywXQBPR64UeuFIYEmRhc2hfYmFzZWxpbmVfMV92MREAdQAA&ccb=9-4&oh=00_AfDuVJbYLuBkoXOJqEaejEJdbEvu5C6AkamHuo1S_4VG2A&oe=64C27AA7&_nc_sid=1d576d&_nc_rid=603e80037b";s:13:"thumbnail_url";s:376:"https://scontent.cdninstagram.com/v/t51.36329-15/363309003_856510055322380_8006815849927378970_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeEFvcMbvwXUj9bio6j5IKBhzBP4lt4XXBjME_iW3hdcGJ_kbn9FqSP9B80hd5bB6HnZLSrcPspwWQ23mkUwlGVX&_nc_ohc=WSPwhfWCVM0AX933-_v&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfAO0YY8RKwph6FGHIt24_7kwJJNxJyigj2XZI60ziDAaA&oe=64C6831A";s:9:"permalink";s:43:"https://www.instagram.com/reel/CvJRhwzv1sI/";s:2:"id";s:17:"18376435978022568";}i:1;a:6:{s:10:"media_type";s:14:"CAROUSEL_ALBUM";s:18:"media_product_type";s:4:"FEED";s:9:"media_url";s:374:"https://scontent.cdninstagram.com/v/t51.2885-15/362627307_974450963838454_141848914603298075_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeEvzUgx9Hx1n_F2Sf7I7Vg_nozs0ZK-6tiejOzRkr7q2MUDrz2Y7MhlXgMV7dii1PnlKAna3gOSgyqJVHNPVLxQ&_nc_ohc=aMnEob9zqcQAX9Fjqlj&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfBCYCdiB1BgNyUDDwulOZFCIUB04IjYZ078OQrKA4Ev_A&oe=64C56746";s:9:"permalink";s:40:"https://www.instagram.com/p/CvHhEHZLMmd/";s:8:"children";a:1:{s:4:"data";a:3:{i:0;a:3:{s:10:"media_type";s:5:"IMAGE";s:9:"media_url";s:374:"https://scontent.cdninstagram.com/v/t51.2885-15/362627307_974450963838454_141848914603298075_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeEvzUgx9Hx1n_F2Sf7I7Vg_nozs0ZK-6tiejOzRkr7q2MUDrz2Y7MhlXgMV7dii1PnlKAna3gOSgyqJVHNPVLxQ&_nc_ohc=aMnEob9zqcQAX9Fjqlj&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfBCYCdiB1BgNyUDDwulOZFCIUB04IjYZ078OQrKA4Ev_A&oe=64C56746";s:2:"id";s:17:"17994786569040572";}i:1;a:3:{s:10:"media_type";s:5:"IMAGE";s:9:"media_url";s:374:"https://scontent.cdninstagram.com/v/t51.2885-15/362779687_825786449120676_565588220546959489_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeGvULMKacefgax_n4aqliQVQBRHfbHB5IVAFEd9scHkhbD6-ZfO3dRFk6DiVquv7eJEz8YegWi88PT69UYyXCPu&_nc_ohc=jJOnvBYNJUEAX91xJjS&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfAeuTZlUeJEUijGixUvsmxdgtmc5jn194Qi2yQ4bcj8wQ&oe=64C4C1BA";s:2:"id";s:17:"18200827981250681";}i:2;a:3:{s:10:"media_type";s:5:"IMAGE";s:9:"media_url";s:375:"https://scontent.cdninstagram.com/v/t51.2885-15/362745410_777435640835274_7659638963414237754_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeGoEfHQ-O_chERMr_T5UqCRmt0czepRyJ6a3RzN6lHInmV_pZB2SqrtSz6PD6SA6DYeMk_80dvZLh39lepJxYBL&_nc_ohc=ws8bUZWwY_kAX9BcjdI&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfCya-HH9UdBMAJFzI19WhbpRDyvnPX9fKkB9QIHchjd5w&oe=64C5DDD6";s:2:"id";s:17:"18218559769243809";}}}s:2:"id";s:17:"17978945249181022";}i:2;a:7:{s:7:"caption";s:38:"Posting a 10 second video to instagram";s:10:"media_type";s:5:"VIDEO";s:18:"media_product_type";s:5:"REELS";s:9:"media_url";s:640:"https://scontent.cdninstagram.com/o1/v/t16/f1/m82/F44748F1BD1CFCCEC7CB01E7BB844B8E_video_dashinit.mp4?efg=eyJ2ZW5jb2RlX3RhZyI6InZ0c192b2RfdXJsZ2VuLjEyODAuY2xpcHMifQ&_nc_ht=scontent.cdninstagram.com&_nc_cat=102&vs=3445824742332912_286087293&_nc_vs=HBksFQIYT2lnX3hwdl9yZWVsc19wZXJtYW5lbnRfcHJvZC9GNDQ3NDhGMUJEMUNGQ0NFQzdDQjAxRTdCQjg0NEI4RV92aWRlb19kYXNoaW5pdC5tcDQVAALIAQAVABgkR09IRHFSVm5FbERJcFVjTkFGQkFQcExfb2w0b2JxX0VBQUFGFQICyAEAKAAYABsBiAd1c2Vfb2lsATEVAAAmtLTk5IX%2F3T8VAigCQzMsF0AkeuFHrhR7GBJkYXNoX2Jhc2VsaW5lXzFfdjERAHUAAA%3D%3D&ccb=9-4&oh=00_AfBz_gd23HXT6MEE5KBMbPadsfS5CfeVybcTyLQozTdn4w&oe=64C27302&_nc_sid=1d576d&_nc_rid=03aeccaee1";s:13:"thumbnail_url";s:376:"https://scontent.cdninstagram.com/v/t51.36329-15/362457490_995143775134446_4921629911250403452_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeGwpOb_DXVqKXqG3piD6-6GRxcT30bwrVJHFxPfRvCtUqRFBMuRAJQ3o1krnVGeY_4wjEhi6ARb9OykKNdFpuEg&_nc_ohc=I0VXNC4fjxEAX84nXCO&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfDdp1giY2QoFfaYjBtUpwXhljx6JXNhiFNpHJoZjhsoZA&oe=64C592E6";s:9:"permalink";s:43:"https://www.instagram.com/reel/CvHO0jPrmjP/";s:2:"id";s:17:"17870979449905511";}i:3;a:6:{s:7:"caption";s:31:"Will this post work in laravel?";s:10:"media_type";s:5:"IMAGE";s:18:"media_product_type";s:4:"FEED";s:9:"media_url";s:376:"https://scontent.cdninstagram.com/v/t51.2885-15/362666650_1786628325128473_8053141953042756899_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeGyaKOt2BMWNAA8t1W8RuoMM5TEgfUmXhgzlMSB9SZeGC-_1f3J4o2MXE8cdeW6DvthUXIJlTZppaTNqXqtmQzZ&_nc_ohc=SOPS8LI9udIAX8eRSd7&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfBGWddxUJrwhYScUVPIrm07ibncRykjtWwoka4jg0v8CQ&oe=64C4F511";s:9:"permalink";s:40:"https://www.instagram.com/p/CvHF7_3rcr4/";s:2:"id";s:17:"18272044753145369";}i:4;a:6:{s:7:"caption";s:35:"This post was made from laravel app";s:10:"media_type";s:5:"IMAGE";s:18:"media_product_type";s:4:"FEED";s:9:"media_url";s:375:"https://scontent.cdninstagram.com/v/t51.2885-15/363017857_820260739675474_8935291149570046443_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeE_aqo1Vkn-Jp_HsFjRer_VtvqMM7kyrPK2-owzuTKs8vIhtdIFuWOB2OFOih0_YOHbUpZIQCqC6kNusIfqcYIA&_nc_ohc=TYHN73FlM60AX8VCpux&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfB-CBFKYk0ibucof02Bh4rFg8Qa6JOdmsTfjXUHQ3wuxw&oe=64C69941";s:9:"permalink";s:40:"https://www.instagram.com/p/CvE5GxDtF2r/";s:2:"id";s:17:"17965299602390090";}i:5;a:6:{s:7:"caption";s:13:"my first post";s:10:"media_type";s:5:"IMAGE";s:18:"media_product_type";s:4:"FEED";s:9:"media_url";s:376:"https://scontent.cdninstagram.com/v/t51.29350-15/362654119_823370339198245_2582382903306098335_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=8ae9d6&_nc_eui2=AeE2Am1YbynuOL9hd2si-9ybxEYRhdYqDJHERhGF1ioMkRlo-qkEhUaJ7LwZ2SvYeNb1na1-_7eP202GnyJWvbA7&_nc_ohc=pGoR-kiJdNoAX8rPAA9&_nc_ht=scontent.cdninstagram.com&edm=AM6HXa8EAAAA&oh=00_AfCwKRf4PS16_j0X25hM3ApMbWBGbTdim6BwrDRGzo5kyQ&oe=64C50A69";s:9:"permalink";s:40:"https://www.instagram.com/p/CvEjXWLSwQg/";s:2:"id";s:17:"18023401999607410";}}');    
    }
}
