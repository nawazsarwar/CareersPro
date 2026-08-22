@extends('layouts.admin')
@section('content')
@can('post_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.posts.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.post.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.post.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Post">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.post.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.advertisement') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.posttype') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.serial_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.subject') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.slug') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.vacancies') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.location') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.pay_level') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.pay_range') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.fee') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.opening_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.closing_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.payment_closing_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.withdrawn') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.added_by') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.test_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.test_reporting_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.gate_closing_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.scheduled_test_start') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.test_duration') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.interview_date') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.interview_time') }}
                    </th>
                    <th>
                        {{ trans('cruds.post.fields.interview_venue') }}
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($advertisements as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($post_types as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
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
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
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
@can('post_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.posts.massDestroy') }}",
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
    ajax: "{{ route('admin.posts.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'advertisement_title', name: 'advertisement.title' },
{ data: 'posttype_name', name: 'posttype.name' },
{ data: 'serial_no', name: 'serial_no' },
{ data: 'title', name: 'title' },
{ data: 'subject', name: 'subject' },
{ data: 'slug', name: 'slug' },
{ data: 'vacancies', name: 'vacancies' },
{ data: 'location', name: 'location' },
{ data: 'pay_level', name: 'pay_level' },
{ data: 'pay_range', name: 'pay_range' },
{ data: 'fee', name: 'fee' },
{ data: 'opening_date', name: 'opening_date' },
{ data: 'closing_date', name: 'closing_date' },
{ data: 'payment_closing_date', name: 'payment_closing_date' },
{ data: 'withdrawn', name: 'withdrawn' },
{ data: 'status', name: 'status' },
{ data: 'remarks', name: 'remarks' },
{ data: 'added_by_name', name: 'added_by.name' },
{ data: 'test_date', name: 'test_date' },
{ data: 'test_reporting_time', name: 'test_reporting_time' },
{ data: 'gate_closing_time', name: 'gate_closing_time' },
{ data: 'scheduled_test_start', name: 'scheduled_test_start' },
{ data: 'test_duration', name: 'test_duration' },
{ data: 'interview_date', name: 'interview_date' },
{ data: 'interview_time', name: 'interview_time' },
{ data: 'interview_venue', name: 'interview_venue' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Post').DataTable(dtOverrideGlobals);
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