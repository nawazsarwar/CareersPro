@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.advertisementType.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.advertisement-types.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.advertisementType.fields.title') }}</label>
                <select class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" name="title" id="title" required>
                    <option value disabled {{ old('title', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\AdvertisementType::TITLE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('title', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.advertisementType.fields.title_helper') }}</span>
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