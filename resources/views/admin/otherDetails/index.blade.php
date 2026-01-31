@extends('layouts.admin')
@section('content')
@can('other_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.other-details.create') }}">
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
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-OtherDetail">
            <thead>
                <tr>
                    <th width="10">

                    </th>
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
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('other_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.other-details.massDestroy') }}",
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
    ajax: "{{ route('admin.other-details.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'user_name', name: 'user.name' },
{ data: 'fellowship_undergraduate', name: 'fellowship_undergraduate' },
{ data: 'fellowship_graduate', name: 'fellowship_graduate' },
{ data: 'fellowship_postgraduate', name: 'fellowship_postgraduate' },
{ data: 'phd_thesis_title', name: 'phd_thesis_title' },
{ data: 'research_phd_awarded', name: 'research_phd_awarded' },
{ data: 'research_phd_thesis', name: 'research_phd_thesis' },
{ data: 'research_phd_total_scholars', name: 'research_phd_total_scholars' },
{ data: 'research_mphil_awarded', name: 'research_mphil_awarded' },
{ data: 'research_mphil_thesis', name: 'research_mphil_thesis' },
{ data: 'research_mphil_total_scholars', name: 'research_mphil_total_scholars' },
{ data: 'research_other_awarded', name: 'research_other_awarded' },
{ data: 'research_other_thesis', name: 'research_other_thesis' },
{ data: 'research_other_total_scholars', name: 'research_other_total_scholars' },
{ data: 'eminent_scholar', name: 'eminent_scholar' },
{ data: 'contribution_to_knowledge', name: 'contribution_to_knowledge' },
{ data: 'engaged_in_research', name: 'engaged_in_research' },
{ data: 'industry_experience', name: 'industry_experience' },
{ data: 'current_pay_level', name: 'current_pay_level' },
{ data: 'current_pay_range', name: 'current_pay_range' },
{ data: 'current_basic_pay', name: 'current_basic_pay' },
{ data: 'current_pay_band', name: 'current_pay_band' },
{ data: 'current_grade_pay', name: 'current_grade_pay' },
{ data: 'current_basic_pay_old', name: 'current_basic_pay_old' },
{ data: 'current_allowances', name: 'current_allowances' },
{ data: 'current_allowances_total', name: 'current_allowances_total' },
{ data: 'increment_date', name: 'increment_date' },
{ data: 'minimum_initial_pay', name: 'minimum_initial_pay' },
{ data: 'joining_time', name: 'joining_time' },
{ data: 'books_published', name: 'books_published' },
{ data: 'books_accepted', name: 'books_accepted' },
{ data: 'papers_published', name: 'papers_published' },
{ data: 'papers_accepted', name: 'papers_accepted' },
{ data: 'articles_published', name: 'articles_published' },
{ data: 'articles_accepted', name: 'articles_accepted' },
{ data: 'papers_read_published', name: 'papers_read_published' },
{ data: 'papers_read_accepted', name: 'papers_read_accepted' },
{ data: 'eca_university_administration', name: 'eca_university_administration' },
{ data: 'eca_student', name: 'eca_student' },
{ data: 'eca_residential_student', name: 'eca_residential_student' },
{ data: 'eca_cultural', name: 'eca_cultural' },
{ data: 'relevant_work', name: 'relevant_work' },
{ data: 'previous_applications', name: 'previous_applications' },
{ data: 'testimonial_1', name: 'testimonial_1' },
{ data: 'testimonial_2', name: 'testimonial_2' },
{ data: 'testimonial_3', name: 'testimonial_3' },
{ data: 'remark_essential_qualification', name: 'remark_essential_qualification' },
{ data: 'remark_essential_qualification_document', name: 'remark_essential_qualification_document', sortable: false, searchable: false },
{ data: 'remark_desirable_qualification', name: 'remark_desirable_qualification' },
{ data: 'remark_desirable_qualification_document', name: 'remark_desirable_qualification_document', sortable: false, searchable: false },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-OtherDetail').DataTable(dtOverrideGlobals);
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