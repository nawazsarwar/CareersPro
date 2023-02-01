@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.post.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.posts.update", [$post->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="advertisement_id">{{ trans('cruds.post.fields.advertisement') }}</label>
                <select class="form-control select2 {{ $errors->has('advertisement') ? 'is-invalid' : '' }}" name="advertisement_id" id="advertisement_id" required>
                    @foreach($advertisements as $id => $entry)
                        <option value="{{ $id }}" {{ (old('advertisement_id') ? old('advertisement_id') : $post->advertisement->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('advertisement'))
                    <span class="text-danger">{{ $errors->first('advertisement') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.advertisement_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="posttype_id">{{ trans('cruds.post.fields.posttype') }}</label>
                <select class="form-control select2 {{ $errors->has('posttype') ? 'is-invalid' : '' }}" name="posttype_id" id="posttype_id" required>
                    @foreach($posttypes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('posttype_id') ? old('posttype_id') : $post->posttype->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('posttype'))
                    <span class="text-danger">{{ $errors->first('posttype') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.posttype_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="serial_no">{{ trans('cruds.post.fields.serial_no') }}</label>
                <input class="form-control {{ $errors->has('serial_no') ? 'is-invalid' : '' }}" type="number" name="serial_no" id="serial_no" value="{{ old('serial_no', $post->serial_no) }}" step="1">
                @if($errors->has('serial_no'))
                    <span class="text-danger">{{ $errors->first('serial_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.serial_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.post.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="slug">{{ trans('cruds.post.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $post->slug) }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.post.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', $post->description) }}</textarea>
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="vacancies">{{ trans('cruds.post.fields.vacancies') }}</label>
                <input class="form-control {{ $errors->has('vacancies') ? 'is-invalid' : '' }}" type="number" name="vacancies" id="vacancies" value="{{ old('vacancies', $post->vacancies) }}" step="1" required>
                @if($errors->has('vacancies'))
                    <span class="text-danger">{{ $errors->first('vacancies') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.vacancies_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="location">{{ trans('cruds.post.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $post->location) }}" required>
                @if($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pay_level">{{ trans('cruds.post.fields.pay_level') }}</label>
                <input class="form-control {{ $errors->has('pay_level') ? 'is-invalid' : '' }}" type="text" name="pay_level" id="pay_level" value="{{ old('pay_level', $post->pay_level) }}" required>
                @if($errors->has('pay_level'))
                    <span class="text-danger">{{ $errors->first('pay_level') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.pay_level_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pay_range">{{ trans('cruds.post.fields.pay_range') }}</label>
                <input class="form-control {{ $errors->has('pay_range') ? 'is-invalid' : '' }}" type="text" name="pay_range" id="pay_range" value="{{ old('pay_range', $post->pay_range) }}" required>
                @if($errors->has('pay_range'))
                    <span class="text-danger">{{ $errors->first('pay_range') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.pay_range_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fee">{{ trans('cruds.post.fields.fee') }}</label>
                <input class="form-control {{ $errors->has('fee') ? 'is-invalid' : '' }}" type="number" name="fee" id="fee" value="{{ old('fee', $post->fee) }}" step="0.01" required>
                @if($errors->has('fee'))
                    <span class="text-danger">{{ $errors->first('fee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.fee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="open_date">{{ trans('cruds.post.fields.open_date') }}</label>
                <input class="form-control datetime {{ $errors->has('open_date') ? 'is-invalid' : '' }}" type="text" name="open_date" id="open_date" value="{{ old('open_date', $post->open_date) }}">
                @if($errors->has('open_date'))
                    <span class="text-danger">{{ $errors->first('open_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.open_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="last_date">{{ trans('cruds.post.fields.last_date') }}</label>
                <input class="form-control datetime {{ $errors->has('last_date') ? 'is-invalid' : '' }}" type="text" name="last_date" id="last_date" value="{{ old('last_date', $post->last_date) }}">
                @if($errors->has('last_date'))
                    <span class="text-danger">{{ $errors->first('last_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.last_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="payment_end_date">{{ trans('cruds.post.fields.payment_end_date') }}</label>
                <input class="form-control datetime {{ $errors->has('payment_end_date') ? 'is-invalid' : '' }}" type="text" name="payment_end_date" id="payment_end_date" value="{{ old('payment_end_date', $post->payment_end_date) }}">
                @if($errors->has('payment_end_date'))
                    <span class="text-danger">{{ $errors->first('payment_end_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.payment_end_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="withdrawn">{{ trans('cruds.post.fields.withdrawn') }}</label>
                <input class="form-control {{ $errors->has('withdrawn') ? 'is-invalid' : '' }}" type="number" name="withdrawn" id="withdrawn" value="{{ old('withdrawn', $post->withdrawn) }}" step="1" required>
                @if($errors->has('withdrawn'))
                    <span class="text-danger">{{ $errors->first('withdrawn') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.withdrawn_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="status">{{ trans('cruds.post.fields.status') }}</label>
                <input class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" type="number" name="status" id="status" value="{{ old('status', $post->status) }}" step="1" required>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.post.fields.remarks') }}</label>
                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks', $post->remarks) }}</textarea>
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.remarks_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="added_by_id">{{ trans('cruds.post.fields.added_by') }}</label>
                <select class="form-control select2 {{ $errors->has('added_by') ? 'is-invalid' : '' }}" name="added_by_id" id="added_by_id" required>
                    @foreach($added_bies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('added_by_id') ? old('added_by_id') : $post->added_by->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('added_by'))
                    <span class="text-danger">{{ $errors->first('added_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.post.fields.added_by_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection