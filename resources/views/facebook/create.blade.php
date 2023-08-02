<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Create a Facebook Post') }}
            </h2>
            <div class="flex">
                <p class="js-submitting-message hidden dark:text-white font-semibold text-sm py-2">
                    Please wait while we are processing...
                </p>
                <button type="button" class="ms-3 js-form-submit inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Save
                    <x-loader/>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="px-8 pb-8 pt-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl dark:text-gray-100">
                    <form enctype="multipart/form-data" action="{{ route('facebook.posts.store') }}" method="POST" class="js-form space-y-4 mt-0">
                        @csrf
                        <div>
                            <x-input-label for="message" :value="__('Share anything!')" class="text-lg font-semibold" />
                            <textarea name="message" id="message" cols="65s" rows="10" class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm p-2">{{ old('message', $form->message) }}</textarea>
                            <x-input-error class="text-sm font-semibold " :messages="$errors->get('message')" />
                        </div>
                        
                        <div>
                            {{-- <x-input-label :value="__('Post with? (We cannot upload for now as Facebook cannot scrape files from local)')" class="text-lg font-semibold mb-4" />  --}}
                            <x-input-label :value="__('Post with?')" class="text-lg font-semibold mb-4" /> 
                            <div class="flex items-center mb-2">
                                <input {{ old('upload', '') == 'none' ? 'checked' : ''}} id="none" type="radio" value="none" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="none" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">None</label>
                            </div>
                            <div class="flex items-center mb-2">
                                <input {{ old('upload', '')  == 'link' ? 'checked' : ''}} id="link" type="radio" value="link" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="link" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Link</label>
                            </div>
                            <div class="flex items-center mb-2">
                                <input {{ old('upload', '') == 'image' ? 'checked' : ''}} id="image" type="radio" value="image" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="image" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Image</label>
                            </div>
                            <div class="flex items-center">
                                <input {{ old('upload', '')  == 'video' ? 'checked' : ''}} id="video" type="radio" value="video" name="upload" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="video" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Video / GIF</label>
                            </div>
                        </div>
                        <div class="js-link-input {{ old('upload', '')  !== 'link' ? 'hidden' : ''}}">
                            <x-input-label for="link" :value="__('Your link ')" class="text-lg font-semibold" />
                            <x-text-input id="link" name="link" type="text" class="mt-1 block w-full" :value="old('link', $form->link)" required autofocus />
                            <x-input-error class="text-sm font-semibold mt-2" :messages="$errors->get('link')" />
                        </div>
                        <div class="js-video-upload {{ old('upload', '')  !== 'video' ? 'hidden' : ''}}">
                            <x-input-label for="media_video" :value="__('Upload a Video / GIF')" class="text-lg font-semibold" />
                            <input type="file" id="media_video" name="media_video" accept="video/*,image/gif" class='mt-1 py-1 block w-full text-white border border-gray-300 rounded-md cursor-pointer dark:bg-gray-900 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400'>
                            @if ($errors->has('media_video'))
                                <x-input-error class="mt-2" :messages="['Supported file types: mp4, mov, gif']" />
                            @endif
                        </div>
                        <div class="js-image-upload {{ old('upload', '')  !== 'image' ? 'hidden' : ''}}">
                            <x-input-label for="media_images" :value="__('Upload an Image')" class="text-lg font-semibold" />
                            <input type="file" id="media_images" multiple name="media_images[]" accept="image/*" class='mt-1 py-1 block w-full text-white border border-gray-300 rounded-md cursor-pointer dark:bg-gray-900 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400'>
                            @if ($errors->has('media_images'))
                                <x-input-error class="mt-2" :messages="$errors->get('media_images')" />
                            @endif
                            @if ($errors->has('media_images.*'))
                                <x-input-error class="mt-2" :messages="['Supported file types: jpg, jpeg, gif, png']" />
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@vite('resources/js/facebook.js')
