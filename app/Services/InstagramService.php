<?php

namespace App\Services;

use App\SingleTons\FacebookApi;

class InstagramService
{
    private $instagram;
    // private $pageId = 'me';
    private $pageId = '108227429009976';

    function __construct()
    {
        $this->instagram = FacebookApi::getInstance()->getApi();
    }

    public function post(array $postData)
    {
        # code...
        dd($postData);
    }

    public function postSingleMedia()
    {
        $images = [
            'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/440px-Cat_November_2010-1a.jpg',
            'https://cdn.britannica.com/16/234216-050-C66F8665/beagle-hound-dog.jpg',
            'https://cdn.download.ams.birds.cornell.edu/api/v1/asset/202984001/1800',
            'https://images.immediate.co.uk/production/volatile/sites/23/2022/09/GettyImages-200386624-001-d80a3ec.jpg?quality=90&webp=true&resize=1750,1167',
            'https://cdn.stg.the-3rd.io/post_images/41/posts/396/LKHE0IRHPA3i6SCtK19qGAWWQGFxIHU22wosRrtL.jpg'
        ];

        $image = array_rand($images);
    }
}
