@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('other_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.other-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.otherDetail.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.otherDetail.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-OtherDetail">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.fellowship_undergraduate') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.fellowship_graduate') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.fellowship_postgraduate') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.phd_thesis_title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_phd_awarded') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_phd_thesis') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_phd_total_scholars') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_mphil_awarded') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_mphil_thesis') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_mphil_total_scholars') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_other_awarded') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_other_thesis') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_other_total_scholars') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eminent_scholar') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.contribution_to_knowledge') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.engaged_in_research') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.industry_experience') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_pay_level') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_pay_range') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_basic_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_pay_band') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_grade_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_basic_pay_old') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_allowances') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_allowances_total') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.increment_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.minimum_initial_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.joining_time') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.books_published') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.books_accepted') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_published') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_accepted') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.articles_published') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.articles_accepted') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_read_published') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_read_accepted') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_university_administration') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_student') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_residential_student') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_cultural') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.relevant_work') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.previous_applications') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.testimonial_1') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.testimonial_2') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.testimonial_3') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_essential_qualification') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_essential_qualification_document') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_desirable_qualification') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_desirable_qualification_document') }}
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
                                @foreach($otherDetails as $key => $otherDetail)
                                    <tr data-entry-id="{{ $otherDetail->id }}">
                                        <td>
                                            {{ $otherDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->fellowship_undergraduate ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->fellowship_graduate ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->fellowship_postgraduate ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->phd_thesis_title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_phd_awarded ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_phd_thesis ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_phd_total_scholars ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_mphil_awarded ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_mphil_thesis ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_mphil_total_scholars ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_other_awarded ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_other_thesis ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->research_other_total_scholars ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->eminent_scholar ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->contribution_to_knowledge ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->engaged_in_research ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->industry_experience ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_pay_level ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_pay_range ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_basic_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_pay_band ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_grade_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_basic_pay_old ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_allowances ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->current_allowances_total ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->increment_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->minimum_initial_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->joining_time ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->books_published ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->books_accepted ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->papers_published ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->papers_accepted ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->articles_published ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->articles_accepted ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->papers_read_published ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->papers_read_accepted ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->eca_university_administration ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->eca_student ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->eca_residential_student ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->eca_cultural ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->relevant_work ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->previous_applications ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->testimonial_1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->testimonial_2 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->testimonial_3 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $otherDetail->remark_essential_qualification ?? '' }}
                                        </td>
                                        <td>
                                            @if($otherDetail->remark_essential_qualification_document)
                                                <a href="{{ $otherDetail->remark_essential_qualification_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $otherDetail->remark_desirable_qualification ?? '' }}
                                        </td>
                                        <td>
                                            @if($otherDetail->remark_desirable_qualification_document)
                                                <a href="{{ $otherDetail->remark_desirable_qualification_document->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('other_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.other-details.show', $otherDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('other_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.other-details.edit', $otherDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('other_detail_delete')
                                                <form action="{{ route('frontend.other-details.destroy', $otherDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('other_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.other-details.massDestroy') }}",
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
  let table = $('.datatable-OtherDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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