@extends('layouts.admin')
@section('content')
@can('academic_qualification_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.academic-qualifications.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.academicQualification.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.academicQualification.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-AcademicQualification">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.course') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.board') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.year') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.division') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.percentage') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.cgpa') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.subjects') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.document') }}
                    </th>
                    <th>
                        {{ trans('cruds.academicQualification.fields.user') }}
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
@can('academic_qualification_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.academic-qualifications.massDestroy') }}",
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
    ajax: "{{ route('admin.academic-qualifications.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'name_name', name: 'name.name' },
{ data: 'course', name: 'course' },
{ data: 'board_name', name: 'board.name' },
{ data: 'year', name: 'year' },
{ data: 'division', name: 'division' },
{ data: 'percentage', name: 'percentage' },
{ data: 'cgpa', name: 'cgpa' },
{ data: 'subjects', name: 'subjects' },
{ data: 'title', name: 'title' },
{ data: 'remarks', name: 'remarks' },
{ data: 'document', name: 'document', sortable: false, searchable: false },
{ data: 'user_name', name: 'user.name' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-AcademicQualification').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection