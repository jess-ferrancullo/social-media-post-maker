<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstagramPostRequest;
use App\Services\FacebookService;
use App\Services\InstagramService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InstagramController extends Controller
{
    function __construct(
        private InstagramService $instagramService,
        private FacebookService $facebookService,
    ){ }
    
    public function index(Request $request)
    {
        $posts = $this->instagramService->getInstagramPosts();
        // dd($posts);
        return view('instagram.index', ['posts' => []]);
    }

    public function create()
    {
        return view('instagram.create', ['form' => new InstagramPostRequest()]);
        # code...
    }

    public function store(InstagramPostRequest $request)
    {
        try {
            $this->instagramService->post($request->all());
            Session::flash('success', 'Your post is now being processed, please wait a few moments and refresh your page in order to see your new post here. Also check in your Instagram if your post shows up');
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e);
            Session::flash('fail', 'There was a problem while posting... Please try again later.');
        }
        
        return redirect()->route('instagram.posts.index');
    }

    // ---- Pages to Connect Facebook To Instagram ---- //

    public function connect()
    {
        $facebookPages = $this->facebookService->getFacebookPages();
        return view('instagram.connect', ['pages' => $facebookPages]);
        # code...
    }

    public function connectToFacebook(Request $request)
    {
        // dd($request->get('page_id'));
        try {
            $this->instagramService->connectFacebookToInstagram($request->get('page_id'));
            Session::flash('success', 'Successfully connected your instagram account to your facebook page');
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e);
            Session::flash('fail', 'There was a problem while trying to connect your selected facebook page with instagram. 
                Please check if you have already connected the page in facebook and try again');
        }
        
        return redirect()->route('instagram.posts.index');
        # code...
    }
}
