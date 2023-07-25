<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Connect your facebook Page to instagram account') }}
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
                    <form action="{{ route('instagram.facebook.connect.store') }}" method="POST" class="js-form space-y-4 mt-0">
                        @csrf
                        <h2 class="text-lg font-semibold">
                            ⚠️ Please make sure that you have setup your instagram account and is connected the the page in facebok.
                            This endpoint is just to get and save the connected instagram id ⚠️
                        </h2>
                        <div>
                            <x-input-label for="page_id" :value="__('Your facebook page')" class="text-lg font-semibold mb-2" />
                            {{-- <label for="pageId" class="block mb-1 text_base font-semibold dark:text-white">Select a Page:</label> --}}
                            <select name="page_id" class="bg-gray-50 p-2.5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            @foreach ($pages as $page)
                                <option  value="{{$page->user_page_id}}" class="font-semibold text-sm">{{ $page->user_page_name }}</option>
                            @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('page_id')" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
