<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwitterPostRequest;
use App\Services\TwitterService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class TwitterController extends Controller
{
    function __construct(
        private TwitterService $twitterService,
    ){ }

    public function index(): View
    {
        return view('twitter.index', ['posts' => []]);
    }

    public function create(): View
    {
        return view('twitter.create', ['form' => new TwitterPostRequest()]);
    }

    public function store(TwitterPostRequest $request): RedirectResponse
    {
        try {
            $result = $this->twitterService->post($request->validated());
            Session::flash('success', 'Your tweet is now being processed, please wait a few moments and refresh your page in order to see your new tweet. Also check in your twitter if your tweet shows up');

            if ($result == null) {
                throw new Exception("");
            }

        } catch (Exception) {
            Session::flash('fail', 'There was a problem while posting... Please try again later.');
        }

        return redirect()->route('twitter.tweets.index');
    }
}
