@extends('layouts.admin')
@section('content')
@can('work_experience_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.work-experiences.create') }}">
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-WorkExperience">
            <thead>
                <tr>
                    <th width="10">

                    </th>
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('work_experience_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.work-experiences.massDestroy') }}",
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
    ajax: "{{ route('admin.work-experiences.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'employer', name: 'employer' },
{ data: 'type', name: 'type' },
{ data: 'designation', name: 'designation' },
{ data: 'from', name: 'from' },
{ data: 'to', name: 'to' },
{ data: 'responsibilities', name: 'responsibilities' },
{ data: 'reason_for_leaving', name: 'reason_for_leaving' },
{ data: 'pay_band', name: 'pay_band' },
{ data: 'basic_pay', name: 'basic_pay' },
{ data: 'gross_pay', name: 'gross_pay' },
{ data: 'user_name', name: 'user.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-WorkExperience').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection