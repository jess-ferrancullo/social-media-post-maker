<?php

namespace App\Http\Livewire;

use App\Services\FacebookService;
use Livewire\Component;

class FacebookTable extends Component
{
    public $posts = [];
    public $pageId = null;
    public $pages = [];

    private FacebookService $facebookService;

    public function boot(FacebookService $facebookService) {
        $this->facebookService = $facebookService;
    }

    function mount($posts, $pages)
    {
        $this->posts = $posts;
        $this->pages = $pages;

        foreach ($this->pages as $page) {
            if ($page->is_active) {
                $this->pageId = $page->user_page_id;
            }
        }
    }

    public function updated($newValue)
    {
        $this->facebookService->setActivePage($this->pageId);
        $this->posts = $this->facebookService->getFacebookPosts();
    }

    public function render()
    {
        return view('livewire.facebook-table');
    }
}
