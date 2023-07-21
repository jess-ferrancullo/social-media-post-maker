<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Create a Instagram Post') }}
            </h2>
            <a href="javascript:void(0)" class="js-form-submit inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Save
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="px-8 pb-8 pt-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl dark:text-gray-100">
                    <form enctype="multipart/form-data" action="{{ route('instagram.posts.store') }}" method="POST" class="js-form space-y-4 mt-0">
                        @csrf
                        <div>
                            <x-input-label for="message" :value="__('Caption for you post')" class="text-lg font-semibold" />
                            <textarea name="message" id="message" cols="65s" rows="10" class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm p-2">{{ old('message', $form->message) }}</textarea>
                        </div>
                        {{-- <div>
                            <x-input-label for="link" :value="__('Add a link?')" class="text-lg font-semibold" />
                            <x-text-input id="link" name="link" type="text" class="mt-1 block w-full" :value="old('link', $form->link)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('link')" />
                        </div> --}}
                        <div>
                            <x-input-label :value="__('Post with an image or video? ')" class="text-lg font-semibold" /> 
                            <p class="text-sm mb-4">
                                (We cannot upload for now as Instagram cannot scrape files from local.<br>
                                Were just gonna use programmatically upload using online images for now)
                            </p>
                            <div class="flex items-center mb-2">
                                <input selected id="image" type="radio" value="image" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="image" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Image</label>
                            </div>
                            <div class="flex items-center mb-2">
                                <input id="video" type="radio" value="video" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="video" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Video</label>
                            </div>
                            <div class="flex items-center">
                                <input id="mixed" type="radio" value="mixed" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="mixed" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mixed</label>
                            </div>
                        </div>

                        <div>
                            <x-input-label :value="__('Post this as?')" class="text-lg font-semibold mb-4" /> 
                            <div class="flex items-center mb-2">
                                <input selected id="wall" type="radio" value="wall" name="post_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="wall" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">To my feed</label>
                            </div>
                            <div class="flex items-center mb-2">
                                <input id="story" type="radio" value="story" name="post_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="story" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Story</label>
                            </div>
                            <div class="flex items-center">
                                <input id="reel" type="radio" value="reel" name="post_type" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="reel" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Reel</label>
                            </div>
                        </div>
                        {{-- <div>
                            <x-input-label for="upload" :value="__('Post with a Picture?')" class="text-lg font-semibold" />
                            <input type="file" id="upload" name="upload" class='mt-1 py-1 block w-full text-white border border-gray-300 rounded-md cursor-pointer dark:bg-gray-900 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400'>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX. 800x400px).</p>
                            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                        </div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
