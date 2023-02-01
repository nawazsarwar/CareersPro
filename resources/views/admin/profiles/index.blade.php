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
                        {{ trans('cruds.profile.fields.spouse_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.marital_status') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.fathers_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.mothers_name') }}
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
                        {{ trans('cruds.profile.fields.mobile_verified_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.alternate_mobile') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.pwd') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.disability_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.disability_percent') }}
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
                        {{ trans('cruds.profile.fields.sub_caste') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.nationality') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.place_of_birth') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.district_of_birth') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.state_of_birth') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.domicile_state') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.domicile_district') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.identity_marks') }}
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
                        {{ trans('cruds.profile.fields.conviction') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.conviction_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.debarred') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.debarred_reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.vigilance') }}
                    </th>
                    <th>
                        {{ trans('cruds.profile.fields.vigilance_reason') }}
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
{ data: 'spouse_name', name: 'spouse_name' },
{ data: 'marital_status_title', name: 'marital_status.title' },
{ data: 'fathers_name', name: 'fathers_name' },
{ data: 'mothers_name', name: 'mothers_name' },
{ data: 'dob', name: 'dob' },
{ data: 'gender', name: 'gender' },
{ data: 'mobile', name: 'mobile' },
{ data: 'mobile_verified_at', name: 'mobile_verified_at' },
{ data: 'alternate_mobile', name: 'alternate_mobile' },
{ data: 'pwd', name: 'pwd' },
{ data: 'disability_type_name', name: 'disability_type.name' },
{ data: 'disability_percent', name: 'disability_percent' },
{ data: 'aadhaar_no', name: 'aadhaar_no' },
{ data: 'religion_name', name: 'religion.name' },
{ data: 'category_name', name: 'category.name' },
{ data: 'caste_name', name: 'caste.name' },
{ data: 'sub_caste', name: 'sub_caste' },
{ data: 'nationality_name', name: 'nationality.name' },
{ data: 'place_of_birth', name: 'place_of_birth' },
{ data: 'district_of_birth_district', name: 'district_of_birth.district' },
{ data: 'state_of_birth_name', name: 'state_of_birth.name' },
{ data: 'domicile_state_name', name: 'domicile_state.name' },
{ data: 'domicile_district_district', name: 'domicile_district.district' },
{ data: 'identity_marks', name: 'identity_marks' },
{ data: 'remarks', name: 'remarks' },
{ data: 'verified', name: 'verified' },
{ data: 'locked', name: 'locked' },
{ data: 'conviction', name: 'conviction' },
{ data: 'conviction_reason', name: 'conviction_reason' },
{ data: 'debarred', name: 'debarred' },
{ data: 'debarred_reason', name: 'debarred_reason' },
{ data: 'vigilance', name: 'vigilance' },
{ data: 'vigilance_reason', name: 'vigilance_reason' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
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