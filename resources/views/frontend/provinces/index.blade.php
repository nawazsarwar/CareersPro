@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('province_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="inline-flex rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600" href="{{ route('frontend.provinces.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.province.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.province.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="w-full text-left text-sm text-gray-500 dark:text-gray-400-responsive">
                        <w-full text-left text-sm text-gray-500 dark:text-gray-400 class=" w-full text-left text-sm text-gray-500 dark:text-gray-400 datatable datatable-Province">
                            <thead class="bg-gray-50 dark:bg-white/5">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.id') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.type') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.name') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.iso_3166_2_in') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.vehicle_code') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.zone') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.capital') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.largest_city') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.statehood') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.population') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.area') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.official_languages') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        {{ trans('cruds.province.fields.additional_official_languages') }}
                                    </th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($provinces as $key => $province)
                                    <tr data-entry-id="{{ $province->id }}">
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->id ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->type ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->name ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->iso_3166_2_in ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->vehicle_code ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->zone ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->capital ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->largest_city ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->statehood ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->population ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->area ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->official_languages ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            {{ $province->additional_official_languages ?? '' }}
                                        </td>
                                        <td class="border-b border-gray-200 px-5 py-4 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-300">
                                            @can('province_show')
                                                <a class="inline-flex rounded bg-brand-500 px-2 py-1 text-xs font-medium text-white hover:bg-brand-600" href="{{ route('frontend.provinces.show', $province->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('province_edit')
                                                <a class="inline-flex rounded bg-blue-500 px-2 py-1 text-xs font-medium text-white hover:bg-blue-600" href="{{ route('frontend.provinces.edit', $province->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('province_delete')
                                                <form action="{{ route('frontend.provinces.destroy', $province->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="inline-flex rounded bg-error-500 px-2 py-1 text-xs font-medium text-white hover:bg-error-600" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </w-full text-left text-sm text-gray-500 dark:text-gray-400>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('province_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.provinces.massDestroy') }}",
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
  let w-full text-left text-sm text-gray-500 dark:text-gray-400 = $('.datatable-Province:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection