<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstagramPostRequest;
use App\Services\InstagramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InstagramController extends Controller
{
    function __construct(private InstagramService $instagramService)
    { }
    
    public function index(Request $request)
    {
        // $posts = $this->instagramService->getInstagramPosts();
        return view('instagram.index', ['posts' => ['data' => []]]);
    }

    public function create()
    {
        return view('instagram.create', ['form' => new InstagramPostRequest()]);
        # code...
    }

    public function store(InstagramPostRequest $request)
    {
        $this->instagramService->post($request->all());
        Session::flash('success', 'Successfully Created new post');
        
        return redirect()->route('instagram.posts.index');
    }
}
