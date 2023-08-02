<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Create a Tweet') }}
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
                    <form enctype="multipart/form-data" action="{{ route('twitter.tweets.store') }}" method="POST" class="js-form space-y-4 mt-0">
                        @csrf
                        <div>
                            <x-input-label for="text" :value="__('Whats on your mind?')" class="text-lg font-semibold" />
                            <textarea name="text" id="text" cols="65s" rows="10" class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm p-2">{{ old('text', $form->text) }}</textarea>
                            <x-input-error class="text-sm font-semibold" :messages="$errors->get('text')" />
                        </div>
                        <div>
                            <x-input-label for="media" :value="__('Post with media?')" class="text-lg font-semibold" />
                            <input type="file" id="media" multiple name="media[]" class='mt-1 py-1 block w-full text-white border border-gray-300 rounded-md cursor-pointer dark:bg-gray-900 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400'>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="file_input_help">Image or Video</p>
                            @if ($errors->has('media'))
                                <x-input-error class="mt-2" :messages="$errors->get('media')" />
                            @endif
                            @if ($errors->has('media.*'))
                                <x-input-error class="mt-2" :messages="['Supported file types: mp4, jpg, jpeg, mov, png, ogg']" />
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@vite('resources/js/instagram.js')