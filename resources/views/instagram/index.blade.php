<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Instagram Posts') }}
            </h2>
            <div>
                <a href="{{ route('instagram.facebook.connect') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Connect your IG account
                </a>
                <a href="{{ route('instagram.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Create a Post
                </a>
            </div>
        </div>
        
        
    </x-slot>

    <div class="py-12">
        @if(session()->has('success'))
            <div class="max-w-fit sm:px-6 lg:px-8 mx-auto p-3 shadow sm:rounded-lg text-base font-semibold bg-green-300 mb-4">
                {{ session()->pull('success') }}
            </div>
        @elseif (session()->has('fail'))
            <div class="max-w-fit sm:px-6 lg:px-8 mx-auto p-3 shadow sm:rounded-lg text-base font-semibold bg-red-300 mb-4">
                {{ session()->pull('fail') }}
            </div>
        @endif
        <div class="lg:max-w-fit mx-auto sm:px-6 lg:px-8 grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($posts as $post)
                <div class="p-8 dark:bg-gray-800 rounded relative">
                    <button 
                        data-modal-target="defaultModal" 
                        data-modal-toggle="defaultModal" 
                        class="hidden js-modal-button" 
                        type="button"
                    >
                        Show modal
                    </button>
                    <img 
                        data-post-id={{ $post['id'] }} 
                        data-media-type={{ $post['media_type'] }} 
                        data-media-product-type={{ $post['media_product_type'] }} 
                        data-media-url={{ $post['media_url']}} 
                        data-permalink={{ $post['permalink']}} 
                        data-caption="{{ isset($post['caption']) ? $post['caption'] : 'This is a carousel post'}}"" 
                        class="js-instagram-post object-cover w-72 h-72 cursor-pointer" 
                        src="{{ $post[$post['media_type'] !== 'VIDEO' ? 'media_url' : 'thumbnail_url'] }}"
                        alt=""
                    >
                    @if ($post['media_type'] === 'VIDEO')
                        <svg style="top:9rem; left:9.3rem" class="absolute" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#fff" class="bi bi-play-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z"/>
                        </svg>
                    @endif
                    @if($post['media_product_type'] === 'REELS')
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-10 right-10" width="20" height="20" fill="#fff" class="bi bi-film" viewBox="0 0 16 16">
                            <path d="M0 1a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V1zm4 0v6h8V1H4zm8 8H4v6h8V9zM1 1v2h2V1H1zm2 3H1v2h2V4zM1 7v2h2V7H1zm2 3H1v2h2v-2zm-2 3v2h2v-2H1zM15 1h-2v2h2V1zm-2 3v2h2V4h-2zm2 3h-2v2h2V7zm-2 3v2h2v-2h-2zm2 3h-2v2h2v-2z"/>
                        </svg>
                    @elseif($post['media_type'] === 'CAROUSEL_ALBUM')
                        <svg class="absolute top-10 right-10" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-collection-play" viewBox="0 0 16 16">
                            <path d="M2 3a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11A.5.5 0 0 0 2 3zm2-2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7A.5.5 0 0 0 4 1zm2.765 5.576A.5.5 0 0 0 6 7v5a.5.5 0 0 0 .765.424l4-2.5a.5.5 0 0 0 0-.848l-4-2.5z"/>
                            <path d="M1.5 14.5A1.5 1.5 0 0 1 0 13V6a1.5 1.5 0 0 1 1.5-1.5h13A1.5 1.5 0 0 1 16 6v7a1.5 1.5 0 0 1-1.5 1.5h-13zm13-1a.5.5 0 0 0 .5-.5V6a.5.5 0 0 0-.5-.5h-13A.5.5 0 0 0 1 6v7a.5.5 0 0 0 .5.5h13z"/>
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
<x-instagram-post-modal />
@vite('resources/js/instagram.js')