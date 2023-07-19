<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FacebookController extends Controller
{
    function __construct(private FacebookService $facebookService)
    { }
    
    public function index(Request $request)
    {
        $posts = $this->facebookService->getFacebookPosts();
        return view('facebook.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('facebook.create', ['form' => new PostRequest]);
        # code...
    }

    public function store(PostRequest $request)
    {
        $this->facebookService->post($request->all());
        Session::flash('success', 'Successfully Created new post');
        
        return redirect()->route('facebook.posts.index');
    }
}
