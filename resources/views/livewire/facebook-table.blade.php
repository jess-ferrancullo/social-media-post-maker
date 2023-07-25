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
    <div class="mb-4">
        <label for="pageId" class="block mb-1 text_base font-semibold dark:text-white">Select a Page:</label>
        <select wire:model="pageId" name="pageId" class="bg-gray-50 p-2.5 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        @foreach ($pages as $page)
            <option  value="{{$page->user_page_id}}" class="font-semibold text-sm">{{ $page->user_page_name }}</option>
        @endforeach
        </select>
    </div>
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