<?php

namespace App\Http\Controllers;

use App\Http\Requests\FacebookPostRequest;
use App\Http\Requests\PageTokenRequest;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FacebookController extends Controller
{
    function __construct(private FacebookService $facebookService)
    {}

    public function index(Request $request)
    {
        $posts = $this->facebookService->getFacebookPosts();
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
        $this->facebookService->post($request->all());
        Session::flash('success', 'Successfully Created new post');
        
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
