@extends('layouts.admin')
@section('content')
@can('profile_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="inline-flex rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600" href="{{ route('admin.profiles.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.profile.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="rounded-2xl border border-gray-200 bg-white shadow-theme-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800 font-bold text-gray-800 dark:text-white">
        {{ trans('cruds.profile.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="p-6">
        <table class=\" w-full text-left text-sm text-gray-500 dark:text-gray-400 ajaxTable datatable datatable-Profile\">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th width="10">

                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.id') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.user') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.first_name') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.middle_name') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.last_name') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.spouse_name') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.marital_status') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.fathers_name') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.mothers_name') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.dob') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.gender') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.mobile') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.mobile_verified_at') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.alternate_mobile') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.pwd') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.disability_type') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.disability_percent') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.aadhaar_no') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.religion') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.category') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.caste') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.sub_caste') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.nationality') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.place_of_birth') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.district_of_birth') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.state_of_birth') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.domicile_state') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.domicile_district') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.identity_marks') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.remarks') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.verified') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.locked') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.conviction') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.conviction_reason') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.debarred') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.debarred_reason') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.vigilance') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        {{ trans('cruds.profile.fields.vigilance_reason') }}
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
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