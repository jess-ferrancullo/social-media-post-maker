<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Create a Facebook Page Token') }}
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
                    <form action="{{ route('facebook.pages.store') }}" method="POST" class="js-form space-y-4 mt-0">
                        @csrf
                        <div>
                            <x-input-label for="user_page_name" :value="__('Name of your Page')" class="text-lg font-semibold" />
                            <x-text-input id="user_page_name" name="user_page_name" type="text" class="mt-1 block w-full" :value="old('user_page_name', $form->user_page_name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('user_page_name')" />
                        </div>
                        <div>
                            <x-input-label for="user_page_id" :value="__('Page ID')" class="text-lg font-semibold" />
                            <x-text-input id="user_page_id" name="user_page_id" type="text" class="mt-1 block w-full" :value="old('user_page_id', $form->user_page_id)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('user_page_id')" />
                        </div>
                        <div>
                            <x-input-label for="access_token" :value="__('Access Token')" class="text-lg font-semibold" />
                            <textarea name="access_token" id="access_token" cols="65" rows="10" class="mt-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm p-2">{{ old('access_token', $form->access_token) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('access_token')" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
