@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('application_form_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.application-forms.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-ApplicationForm">
                            <thead>
                                <tr>
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
                                                <option value="{{ $item }}">{{ $item }}</option>
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
                                                <option value="{{ $item }}">{{ $item }}</option>
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
                                                <option value="{{ $item }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\ApplicationForm::SCRUTINIZED_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
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
                                                <option value="{{ $item }}">{{ $item }}</option>
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
                            <tbody>
                                @foreach($applicationForms as $key => $applicationForm)
                                    <tr data-entry-id="{{ $applicationForm->id }}">
                                        <td>
                                            {{ $applicationForm->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->roll_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->random_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->advertisement->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->advertisement_title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->post->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->post_serial_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->post_title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationForm::GENDER_SELECT[$applicationForm->gender] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->dob ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->mobile ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationForm::DISABILITY_SELECT[$applicationForm->disability] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->disability_type->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->disability_percent ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->aadhaar_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->religion->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->category->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->caste->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->sub_caste ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->nationality->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->permanent_address ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->correspondence_address ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->domicile_district ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->domicile_state->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->basic_details ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->additional_details ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->remarks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->review ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->submitted ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->paid ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationForm::HARDCOPY_RECEIVED_SELECT[$applicationForm->hardcopy_received] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationForm::SCRUTINIZED_SELECT[$applicationForm->scrutinized] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->scrutinized_by->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->scrutiny_remark ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\ApplicationForm::ELIGIBLE_SELECT[$applicationForm->eligible] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->eligibility_remark ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->eligibility_updated_by->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->eligibility_updated_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->order_uid ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->admitcard_downloaded_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->interview_letter_downloaded_at ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->centre_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->centre_code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->centre_address ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->centre_city ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->room_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $applicationForm->seat_no ?? '' }}
                                        </td>
                                        <td>
                                            @can('application_form_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.application-forms.show', $applicationForm->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('application_form_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.application-forms.edit', $applicationForm->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('application_form_delete')
                                                <form action="{{ route('frontend.application-forms.destroy', $applicationForm->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('application_form_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.application-forms.massDestroy') }}",
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
  let table = $('.datatable-ApplicationForm:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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
})

</script>
@endsection