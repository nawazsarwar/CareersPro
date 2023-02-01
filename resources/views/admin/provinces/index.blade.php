@extends('layouts.admin')
@section('content')
@can('province_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.provinces.create') }}">
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Province">
            <thead>
                <tr>
                    <th width="10">

                    </th>
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('province_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.provinces.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
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

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.provinces.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'type', name: 'type' },
{ data: 'name', name: 'name' },
{ data: 'iso_3166_2_in', name: 'iso_3166_2_in' },
{ data: 'vehicle_code', name: 'vehicle_code' },
{ data: 'zone', name: 'zone' },
{ data: 'capital', name: 'capital' },
{ data: 'largest_city', name: 'largest_city' },
{ data: 'statehood', name: 'statehood' },
{ data: 'population', name: 'population' },
{ data: 'area', name: 'area' },
{ data: 'official_languages', name: 'official_languages' },
{ data: 'additional_official_languages', name: 'additional_official_languages' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Province').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection