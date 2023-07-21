<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Instagram Posts') }}
            </h2>
            <a href="{{ route('instagram.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Create a Post
            </a>
        </div>
        
        
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                {{-- <div class="max-w-xl dark:text-gray-100">
                    This is where your facebok posts will show up
                </div> --}}
                @if(session()->has('success'))
                    <div class="p-3 shadow sm:rounded-lg text-base font-semibold bg-green-300 mb-4">
                        {{ session()->pull('success') }}
                    </div>
                @elseif (session()->has('fail'))
                    <div class="p-3 shadow sm:rounded-lg text-base font-semibold bg-red-300 mb-4">
                        {{ session()->pull('success') }}
                    </div>
                @endif
                <div class="bg-white text-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="table w-full mb-3">
                        <div class="table-header-group">
                            <div class="table-row">
                                <div class="table-cell p-3 text-left border border-slate-600"  style="width:25%">Date Created</div>
                                <div class="table-cell p-3 text-left border border-slate-600">Post</div>
                            </div>
                        </div>
                        <div class="table-row-group">
                            @foreach ($posts['data'] as $post)
                                <div class="table-row bg-slate-600">
                                    <div class="table-cell p-3 border border-slate-700"  style="width:25%">{{ Carbon::parse($post['created_time'])->format('M j, Y g:i A') }}</div>
                                    <div class="table-cell p-3 border border-slate-700">
                                        <a href="{{ $post['permalink_url'] }}" class="text-blue-400" target="_blank">
                                            {{ $post['message'] }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
