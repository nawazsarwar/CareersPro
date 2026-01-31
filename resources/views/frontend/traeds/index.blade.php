@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('traed_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.traeds.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.traed.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.traed.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Traed">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.traed.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.teaching_at_bachelors_level_in_years') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.teaching_at_masters_level_in_years') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.research_at_masters_level_in_years') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.research_at_post_doctorals_level_in_years') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.experience_in_educational_administration_in_years') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.traed.fields.any_other_administrative_experience_in_years') }}
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
                                @foreach($traeds as $key => $traed)
                                    <tr data-entry-id="{{ $traed->id }}">
                                        <td>
                                            {{ $traed->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->teaching_at_bachelors_level_in_years ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->teaching_at_masters_level_in_years ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->research_at_masters_level_in_years ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->research_at_post_doctorals_level_in_years ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->experience_in_educational_administration_in_years ?? '' }}
                                        </td>
                                        <td>
                                            {{ $traed->any_other_administrative_experience_in_years ?? '' }}
                                        </td>
                                        <td>
                                            @can('traed_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.traeds.show', $traed->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('traed_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.traeds.edit', $traed->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('traed_delete')
                                                <form action="{{ route('frontend.traeds.destroy', $traed->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('traed_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.traeds.massDestroy') }}",
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
  let table = $('.datatable-Traed:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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