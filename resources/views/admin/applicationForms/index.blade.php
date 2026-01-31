@extends('layouts.admin')
@section('content')
@can('application_form_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.application-forms.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.applicationForm.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.applicationForm.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-ApplicationForm">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.user') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.roll_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.random_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.advertisement') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.advertisement_title') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.post') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.post_serial_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.post_title') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.gender') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.dob') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.mobile') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.disability') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.disability_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.disability_percent') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.aadhaar_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.religion') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.caste') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.sub_caste') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.nationality') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.permanent_address') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.correspondence_address') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.domicile_district') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.domicile_state') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.basic_details') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.additional_details') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.remarks') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.review') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.submitted') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.paid') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.hardcopy_received') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.scrutinized') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.scrutinized_by') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.scrutiny_remark') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.eligible') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.eligibility_remark') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.eligibility_updated_by') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.eligibility_updated_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.order_uid') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.admitcard_downloaded_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.interview_letter_downloaded_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.centre_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.centre_code') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.centre_address') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.centre_city') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.room_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.applicationForm.fields.seat_no') }}
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
                            @foreach($users as $key => $item)
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($advertisements as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($posts as $key => $item)
                                <option value="{{ $item->title }}">{{ $item->title }}</option>
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
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ApplicationForm::GENDER_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ApplicationForm::DISABILITY_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($disability_types as $key => $item)
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($religions as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($categories as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($castes as $key => $item)
                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                    </td>
                    <td>
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($countries as $key => $item)
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
                        <select class="search">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach($provinces as $key => $item)
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
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ApplicationForm::HARDCOPY_RECEIVED_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ApplicationForm::SCRUTINIZED_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
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
                        <select class="search" strict="true">
                            <option value>{{ trans('global.all') }}</option>
                            @foreach(App\Models\ApplicationForm::ELIGIBLE_SELECT as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
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
@can('application_form_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.application-forms.massDestroy') }}",
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
    ajax: "{{ route('admin.application-forms.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name', name: 'user.name' },
{ data: 'roll_no', name: 'roll_no' },
{ data: 'random_no', name: 'random_no' },
{ data: 'advertisement_title', name: 'advertisement.title' },
{ data: 'advertisement_title', name: 'advertisement_title' },
{ data: 'post_title', name: 'post.title' },
{ data: 'post_serial_no', name: 'post_serial_no' },
{ data: 'post_title', name: 'post_title' },
{ data: 'name', name: 'name' },
{ data: 'email', name: 'email' },
{ data: 'gender', name: 'gender' },
{ data: 'dob', name: 'dob' },
{ data: 'mobile', name: 'mobile' },
{ data: 'disability', name: 'disability' },
{ data: 'disability_type_name', name: 'disability_type.name' },
{ data: 'disability_percent', name: 'disability_percent' },
{ data: 'aadhaar_no', name: 'aadhaar_no' },
{ data: 'religion_name', name: 'religion.name' },
{ data: 'category_name', name: 'category.name' },
{ data: 'caste_name', name: 'caste.name' },
{ data: 'sub_caste', name: 'sub_caste' },
{ data: 'nationality_name', name: 'nationality.name' },
{ data: 'permanent_address', name: 'permanent_address' },
{ data: 'correspondence_address', name: 'correspondence_address' },
{ data: 'domicile_district', name: 'domicile_district' },
{ data: 'domicile_state_name', name: 'domicile_state.name' },
{ data: 'basic_details', name: 'basic_details' },
{ data: 'additional_details', name: 'additional_details' },
{ data: 'status', name: 'status' },
{ data: 'remarks', name: 'remarks' },
{ data: 'review', name: 'review' },
{ data: 'submitted', name: 'submitted' },
{ data: 'paid', name: 'paid' },
{ data: 'hardcopy_received', name: 'hardcopy_received' },
{ data: 'scrutinized', name: 'scrutinized' },
{ data: 'scrutinized_by_name', name: 'scrutinized_by.name' },
{ data: 'scrutiny_remark', name: 'scrutiny_remark' },
{ data: 'eligible', name: 'eligible' },
{ data: 'eligibility_remark', name: 'eligibility_remark' },
{ data: 'eligibility_updated_by_name', name: 'eligibility_updated_by.name' },
{ data: 'eligibility_updated_at', name: 'eligibility_updated_at' },
{ data: 'order_uid', name: 'order_uid' },
{ data: 'admitcard_downloaded_at', name: 'admitcard_downloaded_at' },
{ data: 'interview_letter_downloaded_at', name: 'interview_letter_downloaded_at' },
{ data: 'centre_name', name: 'centre_name' },
{ data: 'centre_code', name: 'centre_code' },
{ data: 'centre_address', name: 'centre_address' },
{ data: 'centre_city', name: 'centre_city' },
{ data: 'room_no', name: 'room_no' },
{ data: 'seat_no', name: 'seat_no' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-ApplicationForm').DataTable(dtOverrideGlobals);
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