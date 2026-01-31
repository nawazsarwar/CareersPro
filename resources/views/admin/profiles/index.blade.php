@extends('layouts.admin')
@section('content')
@can('profile_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.profiles.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.profile.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.profile.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Profile">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.first_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.middle_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.last_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.fathers_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.dob') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.mobile') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.pwd') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.aadhaar_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.religion') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.caste') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.nationality') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.verified') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.locked') }}
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
@can('profile_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.profiles.massDestroy') }}",
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
    ajax: "{{ route('admin.profiles.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name', name: 'user.name' },
{ data: 'first_name', name: 'first_name' },
{ data: 'middle_name', name: 'middle_name' },
{ data: 'last_name', name: 'last_name' },
{ data: 'fathers_name', name: 'fathers_name' },
{ data: 'dob', name: 'dob' },
{ data: 'gender', name: 'gender' },
{ data: 'mobile', name: 'mobile' },
{ data: 'pwd', name: 'pwd' },
{ data: 'aadhaar_no', name: 'aadhaar_no' },
{ data: 'religion_name', name: 'religion.name' },
{ data: 'category_name', name: 'category.name' },
{ data: 'caste_name', name: 'caste.name' },
{ data: 'nationality_name', name: 'nationality.name' },
{ data: 'remarks', name: 'remarks' },
{ data: 'verified', name: 'verified' },
{ data: 'locked', name: 'locked' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Profile').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection