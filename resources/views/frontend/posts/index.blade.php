@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('post_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.posts.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.post.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.post.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Post">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.advertisement') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.posttype') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.serial_no') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.title') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.slug') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.description') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.vacancies') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.location') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.pay_level') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.pay_range') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.fee') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.open_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.last_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.payment_last_date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.withdrawn') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.remarks') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.added_by') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $key => $post)
                                    <tr data-entry-id="{{ $post->id }}">
                                        <td>
                                            {{ $post->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->advertisement->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->posttype->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->serial_no ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->title ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->slug ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->description ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->vacancies ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->location ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->pay_level ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->pay_range ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->fee ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->open_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->last_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->payment_last_date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->withdrawn ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->status ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->remarks ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->added_by->name ?? '' }}
                                        </td>
                                        <td>
                                            @can('post_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.posts.show', $post->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('post_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.posts.edit', $post->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('post_delete')
                                                <form action="{{ route('frontend.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('post_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.posts.massDestroy') }}",
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
  let table = $('.datatable-Post:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection