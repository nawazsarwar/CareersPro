@extends('layouts.admin')
@section('content')
@can('adress_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.adresses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.adress.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.adress.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Adress">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.house_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.street') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.landmark') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.locality') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.city') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.postal_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.district') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.province') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.country') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.adress.fields.user') }}
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
@can('adress_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.adresses.massDestroy') }}",
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
    ajax: "{{ route('admin.adresses.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'type', name: 'type' },
{ data: 'house_no', name: 'house_no' },
{ data: 'street', name: 'street' },
{ data: 'landmark', name: 'landmark' },
{ data: 'locality', name: 'locality' },
{ data: 'city', name: 'city' },
{ data: 'postal_code_name', name: 'postal_code.name' },
{ data: 'district', name: 'district' },
{ data: 'province_name', name: 'province.name' },
{ data: 'country_name', name: 'country.name' },
{ data: 'status', name: 'status' },
{ data: 'remarks', name: 'remarks' },
{ data: 'user_name', name: 'user.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Adress').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection