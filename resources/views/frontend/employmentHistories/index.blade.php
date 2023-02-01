@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('employment_history_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.employment-histories.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.employmentHistory.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.employmentHistory.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-EmploymentHistory">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.employer') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.designation') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.from') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.to') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.responsibilities') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.reason_for_leaving') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.pay_band') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.basic_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.gross_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employmentHistory.fields.user') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employmentHistories as $key => $employmentHistory)
                                    <tr data-entry-id="{{ $employmentHistory->id }}">
                                        <td>
                                            {{ $employmentHistory->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->employer ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\EmploymentHistory::TYPE_SELECT[$employmentHistory->type] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->designation ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->from ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->to ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->responsibilities ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->reason_for_leaving ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->pay_band ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->basic_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->gross_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $employmentHistory->user->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('employment_history_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.employment-histories.show', $employmentHistory->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('employment_history_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.employment-histories.edit', $employmentHistory->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('employment_history_delete')
                                                <form action="{{ route('frontend.employment-histories.destroy', $employmentHistory->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('employment_history_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.employment-histories.massDestroy') }}",
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
  let table = $('.datatable-EmploymentHistory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection