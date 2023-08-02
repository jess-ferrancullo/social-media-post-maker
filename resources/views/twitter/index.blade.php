<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight align-middle">
                {{ __('Tweets from your Twitter') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('twitter.tweets.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Create a tweet
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @if(session()->has('success'))
                    <div class="p-3 shadow sm:rounded-lg text-base font-semibold bg-green-300 mb-4">
                        {{ session()->pull('success') }}
                    </div>
                @elseif (session()->has('fail'))
                    <div class="p-3 shadow sm:rounded-lg text-base font-semibold bg-red-300 mb-4">
                        {{ session()->pull('fail') }}
                    </div>
                @endif
                <div class="bg-white text-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <table class="table w-full mb-3">
                        <thead>
                            <tr>
                                <th class="font-semibold p-3 text-left border border-slate-600" style="width:25%">Date Created</th>
                                <th class="font-semibold p-3 text-left border border-slate-600">Post</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                            <tr>
                                <td class="table-cell p-3 border border-slate-700"  style="width:25%">{{ Carbon::parse($post['created_time'])->format('M j, Y g:i A') }}</td>
                                <td class="table-cell p-3 border border-slate-700">
                                    <a href="{{ $post['permalink_url'] }}" class="text-blue-400" target="_blank">
                                        {{ $post['message'] }}
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="table-cell cell-2 p-3 border border-slate-700 text-center">No Posts Available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
