@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('academic_qualification_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.academic-qualifications.create') }}">
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
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-AcademicQualification">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.academicQualification.fields.user') }}
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
                                        <select class="search">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach($qualification_levels as $key => $item)
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
                                            @foreach($boards as $key => $item)
                                                <option value="{{ $item->name }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <select class="search" strict="true">
                                            <option value>{{ trans('global.all') }}</option>
                                            @foreach(App\Models\AcademicQualification::DIVISION_SELECT as $key => $item)
                                                <option value="{{ $item }}">{{ $item }}</option>
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
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($academicQualifications as $key => $academicQualification)
                                    <tr data-entry-id="{{ $academicQualification->id }}">
                                        <td>
                                            {{ $academicQualification->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->name->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->course ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->board->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->year ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\AcademicQualification::DIVISION_SELECT[$academicQualification->division] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->percentage ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->cgpa ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->subjects ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $academicQualification->remarks ?? '' }}
                                        </td>
                                        <td>
                                            @if($academicQualification->document)
                                                <a href="{{ $academicQualification->document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('academic_qualification_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.academic-qualifications.show', $academicQualification->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('academic_qualification_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.academic-qualifications.edit', $academicQualification->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('academic_qualification_delete')
                                                <form action="{{ route('frontend.academic-qualifications.destroy', $academicQualification->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('academic_qualification_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.academic-qualifications.massDestroy') }}",
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
  let table = $('.datatable-AcademicQualification:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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