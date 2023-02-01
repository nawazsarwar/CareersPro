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
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Adress">
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
                <tbody>
                    @foreach($adresses as $key => $adress)
                        <tr data-entry-id="{{ $adress->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $adress->id ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Adress::TYPE_SELECT[$adress->type] ?? '' }}
                            </td>
                            <td>
                                {{ $adress->house_no ?? '' }}
                            </td>
                            <td>
                                {{ $adress->street ?? '' }}
                            </td>
                            <td>
                                {{ $adress->landmark ?? '' }}
                            </td>
                            <td>
                                {{ $adress->locality ?? '' }}
                            </td>
                            <td>
                                {{ $adress->city ?? '' }}
                            </td>
                            <td>
                                {{ $adress->postal_code->name ?? '' }}
                            </td>
                            <td>
                                {{ $adress->district ?? '' }}
                            </td>
                            <td>
                                {{ $adress->province->name ?? '' }}
                            </td>
                            <td>
                                {{ $adress->country->name ?? '' }}
                            </td>
                            <td>
                                {{ $adress->status ?? '' }}
                            </td>
                            <td>
                                {{ $adress->remarks ?? '' }}
                            </td>
                            <td>
                                {{ $adress->user->name ?? '' }}
                            </td>
                            <td>
                                @can('adress_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.adresses.show', $adress->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('adress_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.adresses.edit', $adress->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('adress_delete')
                                    <form action="{{ route('admin.adresses.destroy', $adress->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('adress_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.adresses.massDestroy') }}",
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
  let table = $('.datatable-Adress:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection