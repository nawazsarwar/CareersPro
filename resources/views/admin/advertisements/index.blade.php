@extends('layouts.admin')
@section('content')
@can('advertisement_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.advertisements.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.advertisement.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.advertisement.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Advertisement">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.slug') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.dated') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.type') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.advertisement_url') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.document') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.default_fee') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.default_opening_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.default_closing_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.default_payment_closing_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.added_by') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.approved_by') }}
                    </th>
                    <th>
                        {{ trans('cruds.advertisement.fields.approved_at') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($advertisement_types as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($users as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($users as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                    </td>
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
@can('advertisement_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.advertisements.massDestroy') }}",
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
    ajax: "{{ route('admin.advertisements.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'title', name: 'title' },
{ data: 'slug', name: 'slug' },
{ data: 'dated', name: 'dated' },
{ data: 'type_title', name: 'type.title' },
{ data: 'advertisement_url', name: 'advertisement_url' },
{ data: 'document', name: 'document', sortable: false, searchable: false },
{ data: 'default_fee', name: 'default_fee' },
{ data: 'default_opening_date', name: 'default_opening_date' },
{ data: 'default_closing_date', name: 'default_closing_date' },
{ data: 'default_payment_closing_date', name: 'default_payment_closing_date' },
{ data: 'status', name: 'status' },
{ data: 'remarks', name: 'remarks' },
{ data: 'added_by_name', name: 'added_by.name' },
{ data: 'approved_by_name', name: 'approved_by.name' },
{ data: 'approved_at', name: 'approved_at' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Advertisement').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
});

</script>
@endsection