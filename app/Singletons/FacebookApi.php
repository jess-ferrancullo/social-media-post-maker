<?php

namespace App\SingleTons;

use App\Repositories\FacebookRepository;
use Facebook\Facebook;

class FacebookApi
{
    // Hold the class instance.
    protected static $instance = null;
    protected $api = null;
    
    // The constructor is private
    // to prevent initiation with outer code.
    protected function __construct()
    {
        $this->api = new Facebook([
            'app_id' => env('FACEBOOK_APP_ID'),
            'app_secret' => env('FACEBOOK_APP_SECRET'),
            'default_graph_version' => 'v17.0',
            'default_access_token' => (new FacebookRepository)->getActiveApiToken()->access_token
        ]);
    }
    
    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance(): FacebookApi
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
    
        return self::$instance;
    }

    public function getApi(): Facebook
    {
        return $this->api;
    }

    // public function setAccessToken($accessToken)
    // {
    //     $this->api->setDefaultAccessToken($accessToken);
    // }
}
