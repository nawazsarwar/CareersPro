@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('province_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.provinces.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Province">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.province.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.iso_3166_2_in') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.vehicle_code') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.zone') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.capital') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.largest_city') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.statehood') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.population') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.area') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.official_languages') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.province.fields.additional_official_languages') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($provinces as $key => $province)
                                    <tr data-entry-id="{{ $province->id }}">
                                        <td>
                                            {{ $province->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->type ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->iso_3166_2_in ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->vehicle_code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->zone ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->capital ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->largest_city ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->statehood ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->population ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->area ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->official_languages ?? '' }}
                                        </td>
                                        <td>
                                            {{ $province->additional_official_languages ?? '' }}
                                        </td>
                                        <td>
                                            @can('province_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.provinces.show', $province->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('province_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.provinces.edit', $province->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('province_delete')
                                                <form action="{{ route('frontend.provinces.destroy', $province->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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
  let table = $('.datatable-Province:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection