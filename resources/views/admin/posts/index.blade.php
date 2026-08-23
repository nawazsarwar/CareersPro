@extends('layouts.app')

@section('content')
<div x-data="{ selectedIds: [] }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ trans('cruds.post.title_singular') }} {{ trans('global.list') }}</h1>
        @can('post_create')
            <a class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition" href="{{ route('admin.posts.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.post.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-8 overflow-x-auto">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 mb-4">
            <input type="text" name="search" placeholder="{{ trans('global.search') }} by Title or Subject..." class="flex-1 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 focus:ring focus:ring-blue-500" value="{{ request('search') }}">
            <select name="status" class="rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2">
                <option value="">All Statuses</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow transition">Filter</button>
            <a href="{{ route('admin.posts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded shadow transition">Reset</a>
        </form>

        @can('post_delete')
        <button x-show="selectedIds.length > 0"
                @click="if(confirm('{{ trans('global.areYouSure') }}')) {
                    fetch('{{ route('admin.posts.massDestroy') }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ids: selectedIds })
                    }).then(response => { if(response.ok) window.location.reload(); });
                }"
                class="mb-4 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow transition" style="display: none;">
            {{ trans('global.datatables.delete') }} Selected (<span x-text="selectedIds.length"></span>)
        </button>
        @endcan

        <div class="border rounded-lg border-gray-200 dark:border-gray-700 w-full overflow-x-scroll">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm whitespace-nowrap min-w-max">
                <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox"
                                   @change="selectedIds = $event.target.checked ? {{ json_encode($posts->pluck('id')) }} : []"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.title') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.subject') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.vacancies') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.pay_level') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.location') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.fee') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.opening_date') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.closing_date') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.test_date') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.interview_venue') }}</th>
                        <th class="px-4 py-3 text-left font-medium uppercase tracking-wider">{{ trans('cruds.post.fields.status') }}</th>
                        <th class="px-4 py-3 text-right font-medium uppercase tracking-wider">{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" :value="{{ $post->id }}" x-model="selectedIds" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-4 py-3">{{ $post->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $post->title }}</td>
                        <td class="px-4 py-3">{{ $post->subject ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->vacancies }}</td>
                        <td class="px-4 py-3">{{ $post->pay_level ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->location ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->fee ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->opening_date ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->closing_date ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->test_date ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $post->interview_venue ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $post->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $post->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            @can('post_show')
                                <a href="{{ route('admin.posts.show', $post->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ trans('global.view') }}</a>
                            @endcan
                            @can('post_edit')
                                <a href="{{ route('admin.posts.edit', $post->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">{{ trans('global.edit') }}</a>
                            @endcan
                            @can('post_delete')
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">{{ trans('global.delete') }}</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            {{ trans('global.no_results') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
