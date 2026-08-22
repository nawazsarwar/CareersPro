@extends('layouts.admin')
@section('content')
@can('post_type_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="inline-flex rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600" href="{{ route('admin.post-types.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.postType.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('cruds.postType.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="p-6">
        <div class="w-full text-left text-sm text-gray-500 overflow-x-auto">
            <table class=\" w-full text-left text-sm text-gray-500 dark:text-gray-400 datatable datatable-PostType\">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th width="10">

                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.postType.fields.id') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.postType.fields.name') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.postType.fields.pdf_template') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.postType.fields.submission_venue') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.postType.fields.status') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ trans('cruds.postType.fields.remarks') }}
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($postTypes as $key => $postType)
                        <tr data-entry-id="{{ $postType->id }}">
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">

                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                {{ $postType->id ?? '' }}
                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                {{ $postType->name ?? '' }}
                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                {{ $postType->pdf_template ?? '' }}
                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                {{ $postType->submission_venue ?? '' }}
                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                {{ $postType->status ?? '' }}
                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                {{ $postType->remarks ?? '' }}
                            </td>
                            <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                @can('post_type_show')
                                    <a class="inline-flex rounded bg-brand-500 px-2 py-1 text-xs font-medium text-white hover:bg-brand-600" href="{{ route('admin.post-types.show', $postType->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('post_type_edit')
                                    <a class="inline-flex rounded bg-blue-500 px-2 py-1 text-xs font-medium text-white hover:bg-blue-600" href="{{ route('admin.post-types.edit', $postType->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('post_type_delete')
                                    <form action="{{ route('admin.post-types.destroy', $postType->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="inline-flex rounded bg-error-500 px-2 py-1 text-xs font-medium text-white hover:bg-error-600" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('post_type_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.post-types.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-PostType:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection