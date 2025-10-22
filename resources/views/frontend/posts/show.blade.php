@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.post.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.posts.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $post->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.advertisement') }}
                                    </th>
                                    <td>
                                        {{ $post->advertisement->title ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.posttype') }}
                                    </th>
                                    <td>
                                        {{ $post->posttype->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.serial_no') }}
                                    </th>
                                    <td>
                                        {{ $post->serial_no }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.title') }}
                                    </th>
                                    <td>
                                        {{ $post->title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.subject') }}
                                    </th>
                                    <td>
                                        {{ $post->subject }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.slug') }}
                                    </th>
                                    <td>
                                        {{ $post->slug }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.description') }}
                                    </th>
                                    <td>
                                        {{ $post->description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.vacancies') }}
                                    </th>
                                    <td>
                                        {{ $post->vacancies }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.location') }}
                                    </th>
                                    <td>
                                        {{ $post->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.pay_level') }}
                                    </th>
                                    <td>
                                        {{ $post->pay_level }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.pay_range') }}
                                    </th>
                                    <td>
                                        {{ $post->pay_range }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.fee') }}
                                    </th>
                                    <td>
                                        {{ $post->fee }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.opening_date') }}
                                    </th>
                                    <td>
                                        {{ $post->opening_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.closing_date') }}
                                    </th>
                                    <td>
                                        {{ $post->closing_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.payment_closing_date') }}
                                    </th>
                                    <td>
                                        {{ $post->payment_closing_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.withdrawn') }}
                                    </th>
                                    <td>
                                        {{ $post->withdrawn }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.status') }}
                                    </th>
                                    <td>
                                        {{ $post->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.remarks') }}
                                    </th>
                                    <td>
                                        {{ $post->remarks }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.added_by') }}
                                    </th>
                                    <td>
                                        {{ $post->added_by->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.test_date') }}
                                    </th>
                                    <td>
                                        {{ $post->test_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.test_reporting_time') }}
                                    </th>
                                    <td>
                                        {{ $post->test_reporting_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.gate_closing_time') }}
                                    </th>
                                    <td>
                                        {{ $post->gate_closing_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.scheduled_test_start') }}
                                    </th>
                                    <td>
                                        {{ $post->scheduled_test_start }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.test_duration') }}
                                    </th>
                                    <td>
                                        {{ $post->test_duration }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.interview_date') }}
                                    </th>
                                    <td>
                                        {{ $post->interview_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.interview_time') }}
                                    </th>
                                    <td>
                                        {{ $post->interview_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.post.fields.interview_venue') }}
                                    </th>
                                    <td>
                                        {{ $post->interview_venue }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.posts.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection