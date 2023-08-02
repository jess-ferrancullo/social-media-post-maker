<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacebookPostRequest;
use App\Http\Requests\PageTokenRequest;
use App\Services\FacebookService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FacebookController extends Controller
{
    function __construct(private FacebookService $facebookService)
    {}

    public function index()
    {
        // $posts = $this->facebookService->getFacebookPosts();
        $posts = [];
        $pages = $this->facebookService->getFacebookPages();

        return view('facebook.index', [
            'posts' => $posts, 
            'pages' => $pages,
        ]);
    }

    public function create()
    {
        return view('facebook.create', ['form' => new FacebookPostRequest]);
    }

    public function store(FacebookPostRequest $request)
    {
        try {
            $this->facebookService->post($request->all());
            Session::flash('success', 'Your post is now being processed, please wait a few moments and refresh your page in order to see your new post here. Also check in your Facebook Page if your post shows up');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Session::flash('fail', 'There was a problem while posting... Please try again later.');
        }
        
        return redirect()->route('facebook.posts.index');
    }

    public function createPageToken()
    {
        return view('facebook.page-token.create', ['form' => new PageTokenRequest()]);
    }

    public function savePageToken(PageTokenRequest $request)
    {
        $this->facebookService->savePageToken($request->validated());
        Session::flash('success', 'Successfully Created new Facebook Token');

        return redirect()->route('facebook.posts.index'); 
    }
}
