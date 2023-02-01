@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('work_experience_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.work-experiences.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.workExperience.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.workExperience.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-WorkExperience">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.employer') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.designation') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.from') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.to') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.responsibilities') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.reason_for_leaving') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.pay_band') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.basic_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.gross_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.workExperience.fields.user') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workExperiences as $key => $workExperience)
                                    <tr data-entry-id="{{ $workExperience->id }}">
                                        <td>
                                            {{ $workExperience->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->employer ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\WorkExperience::TYPE_SELECT[$workExperience->type] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->designation ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->from ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->to ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->responsibilities ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->reason_for_leaving ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->pay_band ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->basic_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->gross_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $workExperience->user->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('work_experience_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.work-experiences.show', $workExperience->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('work_experience_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.work-experiences.edit', $workExperience->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('work_experience_delete')
                                                <form action="{{ route('frontend.work-experiences.destroy', $workExperience->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('work_experience_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.work-experiences.massDestroy') }}",
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
  let table = $('.datatable-WorkExperience:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection