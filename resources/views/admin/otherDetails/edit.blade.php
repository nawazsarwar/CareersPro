@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.otherDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.other-details.update", [$otherDetail->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.otherDetail.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $otherDetail->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fellowship_undergraduate">{{ trans('cruds.otherDetail.fields.fellowship_undergraduate') }}</label>
                <input class="form-control {{ $errors->has('fellowship_undergraduate') ? 'is-invalid' : '' }}" type="text" name="fellowship_undergraduate" id="fellowship_undergraduate" value="{{ old('fellowship_undergraduate', $otherDetail->fellowship_undergraduate) }}" required>
                @if($errors->has('fellowship_undergraduate'))
                    <span class="text-danger">{{ $errors->first('fellowship_undergraduate') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.fellowship_undergraduate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fellowship_graduate">{{ trans('cruds.otherDetail.fields.fellowship_graduate') }}</label>
                <input class="form-control {{ $errors->has('fellowship_graduate') ? 'is-invalid' : '' }}" type="text" name="fellowship_graduate" id="fellowship_graduate" value="{{ old('fellowship_graduate', $otherDetail->fellowship_graduate) }}">
                @if($errors->has('fellowship_graduate'))
                    <span class="text-danger">{{ $errors->first('fellowship_graduate') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.fellowship_graduate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="fellowship_postgraduate">{{ trans('cruds.otherDetail.fields.fellowship_postgraduate') }}</label>
                <input class="form-control {{ $errors->has('fellowship_postgraduate') ? 'is-invalid' : '' }}" type="text" name="fellowship_postgraduate" id="fellowship_postgraduate" value="{{ old('fellowship_postgraduate', $otherDetail->fellowship_postgraduate) }}">
                @if($errors->has('fellowship_postgraduate'))
                    <span class="text-danger">{{ $errors->first('fellowship_postgraduate') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.fellowship_postgraduate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phd_thesis_title">{{ trans('cruds.otherDetail.fields.phd_thesis_title') }}</label>
                <input class="form-control {{ $errors->has('phd_thesis_title') ? 'is-invalid' : '' }}" type="text" name="phd_thesis_title" id="phd_thesis_title" value="{{ old('phd_thesis_title', $otherDetail->phd_thesis_title) }}">
                @if($errors->has('phd_thesis_title'))
                    <span class="text-danger">{{ $errors->first('phd_thesis_title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.phd_thesis_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_phd_awarded">{{ trans('cruds.otherDetail.fields.research_phd_awarded') }}</label>
                <input class="form-control {{ $errors->has('research_phd_awarded') ? 'is-invalid' : '' }}" type="number" name="research_phd_awarded" id="research_phd_awarded" value="{{ old('research_phd_awarded', $otherDetail->research_phd_awarded) }}" step="1">
                @if($errors->has('research_phd_awarded'))
                    <span class="text-danger">{{ $errors->first('research_phd_awarded') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_phd_awarded_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_phd_thesis">{{ trans('cruds.otherDetail.fields.research_phd_thesis') }}</label>
                <input class="form-control {{ $errors->has('research_phd_thesis') ? 'is-invalid' : '' }}" type="number" name="research_phd_thesis" id="research_phd_thesis" value="{{ old('research_phd_thesis', $otherDetail->research_phd_thesis) }}" step="1">
                @if($errors->has('research_phd_thesis'))
                    <span class="text-danger">{{ $errors->first('research_phd_thesis') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_phd_thesis_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_phd_total_scholars">{{ trans('cruds.otherDetail.fields.research_phd_total_scholars') }}</label>
                <input class="form-control {{ $errors->has('research_phd_total_scholars') ? 'is-invalid' : '' }}" type="number" name="research_phd_total_scholars" id="research_phd_total_scholars" value="{{ old('research_phd_total_scholars', $otherDetail->research_phd_total_scholars) }}" step="1">
                @if($errors->has('research_phd_total_scholars'))
                    <span class="text-danger">{{ $errors->first('research_phd_total_scholars') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_phd_total_scholars_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_mphil_awarded">{{ trans('cruds.otherDetail.fields.research_mphil_awarded') }}</label>
                <input class="form-control {{ $errors->has('research_mphil_awarded') ? 'is-invalid' : '' }}" type="number" name="research_mphil_awarded" id="research_mphil_awarded" value="{{ old('research_mphil_awarded', $otherDetail->research_mphil_awarded) }}" step="1">
                @if($errors->has('research_mphil_awarded'))
                    <span class="text-danger">{{ $errors->first('research_mphil_awarded') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_mphil_awarded_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_mphil_thesis">{{ trans('cruds.otherDetail.fields.research_mphil_thesis') }}</label>
                <input class="form-control {{ $errors->has('research_mphil_thesis') ? 'is-invalid' : '' }}" type="number" name="research_mphil_thesis" id="research_mphil_thesis" value="{{ old('research_mphil_thesis', $otherDetail->research_mphil_thesis) }}" step="1">
                @if($errors->has('research_mphil_thesis'))
                    <span class="text-danger">{{ $errors->first('research_mphil_thesis') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_mphil_thesis_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_mphil_total_scholars">{{ trans('cruds.otherDetail.fields.research_mphil_total_scholars') }}</label>
                <input class="form-control {{ $errors->has('research_mphil_total_scholars') ? 'is-invalid' : '' }}" type="number" name="research_mphil_total_scholars" id="research_mphil_total_scholars" value="{{ old('research_mphil_total_scholars', $otherDetail->research_mphil_total_scholars) }}" step="1">
                @if($errors->has('research_mphil_total_scholars'))
                    <span class="text-danger">{{ $errors->first('research_mphil_total_scholars') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_mphil_total_scholars_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_other_awarded">{{ trans('cruds.otherDetail.fields.research_other_awarded') }}</label>
                <input class="form-control {{ $errors->has('research_other_awarded') ? 'is-invalid' : '' }}" type="number" name="research_other_awarded" id="research_other_awarded" value="{{ old('research_other_awarded', $otherDetail->research_other_awarded) }}" step="1">
                @if($errors->has('research_other_awarded'))
                    <span class="text-danger">{{ $errors->first('research_other_awarded') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_other_awarded_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_other_thesis">{{ trans('cruds.otherDetail.fields.research_other_thesis') }}</label>
                <input class="form-control {{ $errors->has('research_other_thesis') ? 'is-invalid' : '' }}" type="number" name="research_other_thesis" id="research_other_thesis" value="{{ old('research_other_thesis', $otherDetail->research_other_thesis) }}" step="1">
                @if($errors->has('research_other_thesis'))
                    <span class="text-danger">{{ $errors->first('research_other_thesis') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_other_thesis_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="research_other_total_scholars">{{ trans('cruds.otherDetail.fields.research_other_total_scholars') }}</label>
                <input class="form-control {{ $errors->has('research_other_total_scholars') ? 'is-invalid' : '' }}" type="number" name="research_other_total_scholars" id="research_other_total_scholars" value="{{ old('research_other_total_scholars', $otherDetail->research_other_total_scholars) }}" step="1">
                @if($errors->has('research_other_total_scholars'))
                    <span class="text-danger">{{ $errors->first('research_other_total_scholars') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.research_other_total_scholars_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eminent_scholar">{{ trans('cruds.otherDetail.fields.eminent_scholar') }}</label>
                <textarea class="form-control {{ $errors->has('eminent_scholar') ? 'is-invalid' : '' }}" name="eminent_scholar" id="eminent_scholar">{{ old('eminent_scholar', $otherDetail->eminent_scholar) }}</textarea>
                @if($errors->has('eminent_scholar'))
                    <span class="text-danger">{{ $errors->first('eminent_scholar') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.eminent_scholar_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contribution_to_knowledge">{{ trans('cruds.otherDetail.fields.contribution_to_knowledge') }}</label>
                <textarea class="form-control {{ $errors->has('contribution_to_knowledge') ? 'is-invalid' : '' }}" name="contribution_to_knowledge" id="contribution_to_knowledge">{{ old('contribution_to_knowledge', $otherDetail->contribution_to_knowledge) }}</textarea>
                @if($errors->has('contribution_to_knowledge'))
                    <span class="text-danger">{{ $errors->first('contribution_to_knowledge') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.contribution_to_knowledge_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="engaged_in_research">{{ trans('cruds.otherDetail.fields.engaged_in_research') }}</label>
                <textarea class="form-control {{ $errors->has('engaged_in_research') ? 'is-invalid' : '' }}" name="engaged_in_research" id="engaged_in_research">{{ old('engaged_in_research', $otherDetail->engaged_in_research) }}</textarea>
                @if($errors->has('engaged_in_research'))
                    <span class="text-danger">{{ $errors->first('engaged_in_research') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.engaged_in_research_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="industry_experience">{{ trans('cruds.otherDetail.fields.industry_experience') }}</label>
                <textarea class="form-control {{ $errors->has('industry_experience') ? 'is-invalid' : '' }}" name="industry_experience" id="industry_experience">{{ old('industry_experience', $otherDetail->industry_experience) }}</textarea>
                @if($errors->has('industry_experience'))
                    <span class="text-danger">{{ $errors->first('industry_experience') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.industry_experience_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_pay_level">{{ trans('cruds.otherDetail.fields.current_pay_level') }}</label>
                <input class="form-control {{ $errors->has('current_pay_level') ? 'is-invalid' : '' }}" type="text" name="current_pay_level" id="current_pay_level" value="{{ old('current_pay_level', $otherDetail->current_pay_level) }}">
                @if($errors->has('current_pay_level'))
                    <span class="text-danger">{{ $errors->first('current_pay_level') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_pay_level_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_pay_range">{{ trans('cruds.otherDetail.fields.current_pay_range') }}</label>
                <input class="form-control {{ $errors->has('current_pay_range') ? 'is-invalid' : '' }}" type="text" name="current_pay_range" id="current_pay_range" value="{{ old('current_pay_range', $otherDetail->current_pay_range) }}">
                @if($errors->has('current_pay_range'))
                    <span class="text-danger">{{ $errors->first('current_pay_range') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_pay_range_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_basic_pay">{{ trans('cruds.otherDetail.fields.current_basic_pay') }}</label>
                <input class="form-control {{ $errors->has('current_basic_pay') ? 'is-invalid' : '' }}" type="text" name="current_basic_pay" id="current_basic_pay" value="{{ old('current_basic_pay', $otherDetail->current_basic_pay) }}">
                @if($errors->has('current_basic_pay'))
                    <span class="text-danger">{{ $errors->first('current_basic_pay') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_basic_pay_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_pay_band">{{ trans('cruds.otherDetail.fields.current_pay_band') }}</label>
                <input class="form-control {{ $errors->has('current_pay_band') ? 'is-invalid' : '' }}" type="text" name="current_pay_band" id="current_pay_band" value="{{ old('current_pay_band', $otherDetail->current_pay_band) }}">
                @if($errors->has('current_pay_band'))
                    <span class="text-danger">{{ $errors->first('current_pay_band') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_pay_band_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_grade_pay">{{ trans('cruds.otherDetail.fields.current_grade_pay') }}</label>
                <input class="form-control {{ $errors->has('current_grade_pay') ? 'is-invalid' : '' }}" type="text" name="current_grade_pay" id="current_grade_pay" value="{{ old('current_grade_pay', $otherDetail->current_grade_pay) }}">
                @if($errors->has('current_grade_pay'))
                    <span class="text-danger">{{ $errors->first('current_grade_pay') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_grade_pay_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_basic_pay_old">{{ trans('cruds.otherDetail.fields.current_basic_pay_old') }}</label>
                <input class="form-control {{ $errors->has('current_basic_pay_old') ? 'is-invalid' : '' }}" type="text" name="current_basic_pay_old" id="current_basic_pay_old" value="{{ old('current_basic_pay_old', $otherDetail->current_basic_pay_old) }}">
                @if($errors->has('current_basic_pay_old'))
                    <span class="text-danger">{{ $errors->first('current_basic_pay_old') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_basic_pay_old_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_allowances">{{ trans('cruds.otherDetail.fields.current_allowances') }}</label>
                <input class="form-control {{ $errors->has('current_allowances') ? 'is-invalid' : '' }}" type="text" name="current_allowances" id="current_allowances" value="{{ old('current_allowances', $otherDetail->current_allowances) }}">
                @if($errors->has('current_allowances'))
                    <span class="text-danger">{{ $errors->first('current_allowances') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_allowances_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="current_allowances_total">{{ trans('cruds.otherDetail.fields.current_allowances_total') }}</label>
                <input class="form-control {{ $errors->has('current_allowances_total') ? 'is-invalid' : '' }}" type="text" name="current_allowances_total" id="current_allowances_total" value="{{ old('current_allowances_total', $otherDetail->current_allowances_total) }}">
                @if($errors->has('current_allowances_total'))
                    <span class="text-danger">{{ $errors->first('current_allowances_total') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.current_allowances_total_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="increment_date">{{ trans('cruds.otherDetail.fields.increment_date') }}</label>
                <input class="form-control date {{ $errors->has('increment_date') ? 'is-invalid' : '' }}" type="text" name="increment_date" id="increment_date" value="{{ old('increment_date', $otherDetail->increment_date) }}">
                @if($errors->has('increment_date'))
                    <span class="text-danger">{{ $errors->first('increment_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.increment_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="minimum_initial_pay">{{ trans('cruds.otherDetail.fields.minimum_initial_pay') }}</label>
                <textarea class="form-control {{ $errors->has('minimum_initial_pay') ? 'is-invalid' : '' }}" name="minimum_initial_pay" id="minimum_initial_pay">{{ old('minimum_initial_pay', $otherDetail->minimum_initial_pay) }}</textarea>
                @if($errors->has('minimum_initial_pay'))
                    <span class="text-danger">{{ $errors->first('minimum_initial_pay') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.minimum_initial_pay_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="joining_time">{{ trans('cruds.otherDetail.fields.joining_time') }}</label>
                <input class="form-control {{ $errors->has('joining_time') ? 'is-invalid' : '' }}" type="text" name="joining_time" id="joining_time" value="{{ old('joining_time', $otherDetail->joining_time) }}">
                @if($errors->has('joining_time'))
                    <span class="text-danger">{{ $errors->first('joining_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.joining_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="books_published">{{ trans('cruds.otherDetail.fields.books_published') }}</label>
                <input class="form-control {{ $errors->has('books_published') ? 'is-invalid' : '' }}" type="number" name="books_published" id="books_published" value="{{ old('books_published', $otherDetail->books_published) }}" step="1">
                @if($errors->has('books_published'))
                    <span class="text-danger">{{ $errors->first('books_published') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.books_published_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="books_accepted">{{ trans('cruds.otherDetail.fields.books_accepted') }}</label>
                <input class="form-control {{ $errors->has('books_accepted') ? 'is-invalid' : '' }}" type="number" name="books_accepted" id="books_accepted" value="{{ old('books_accepted', $otherDetail->books_accepted) }}" step="1">
                @if($errors->has('books_accepted'))
                    <span class="text-danger">{{ $errors->first('books_accepted') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.books_accepted_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="papers_published">{{ trans('cruds.otherDetail.fields.papers_published') }}</label>
                <input class="form-control {{ $errors->has('papers_published') ? 'is-invalid' : '' }}" type="number" name="papers_published" id="papers_published" value="{{ old('papers_published', $otherDetail->papers_published) }}" step="1">
                @if($errors->has('papers_published'))
                    <span class="text-danger">{{ $errors->first('papers_published') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_published_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="papers_accepted">{{ trans('cruds.otherDetail.fields.papers_accepted') }}</label>
                <input class="form-control {{ $errors->has('papers_accepted') ? 'is-invalid' : '' }}" type="number" name="papers_accepted" id="papers_accepted" value="{{ old('papers_accepted', $otherDetail->papers_accepted) }}" step="1">
                @if($errors->has('papers_accepted'))
                    <span class="text-danger">{{ $errors->first('papers_accepted') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_accepted_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="articles_published">{{ trans('cruds.otherDetail.fields.articles_published') }}</label>
                <input class="form-control {{ $errors->has('articles_published') ? 'is-invalid' : '' }}" type="number" name="articles_published" id="articles_published" value="{{ old('articles_published', $otherDetail->articles_published) }}" step="1">
                @if($errors->has('articles_published'))
                    <span class="text-danger">{{ $errors->first('articles_published') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.articles_published_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="articles_accepted">{{ trans('cruds.otherDetail.fields.articles_accepted') }}</label>
                <input class="form-control {{ $errors->has('articles_accepted') ? 'is-invalid' : '' }}" type="number" name="articles_accepted" id="articles_accepted" value="{{ old('articles_accepted', $otherDetail->articles_accepted) }}" step="1">
                @if($errors->has('articles_accepted'))
                    <span class="text-danger">{{ $errors->first('articles_accepted') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.articles_accepted_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="papers_read_published">{{ trans('cruds.otherDetail.fields.papers_read_published') }}</label>
                <input class="form-control {{ $errors->has('papers_read_published') ? 'is-invalid' : '' }}" type="number" name="papers_read_published" id="papers_read_published" value="{{ old('papers_read_published', $otherDetail->papers_read_published) }}" step="1">
                @if($errors->has('papers_read_published'))
                    <span class="text-danger">{{ $errors->first('papers_read_published') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_read_published_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="papers_read_accepted">{{ trans('cruds.otherDetail.fields.papers_read_accepted') }}</label>
                <input class="form-control {{ $errors->has('papers_read_accepted') ? 'is-invalid' : '' }}" type="number" name="papers_read_accepted" id="papers_read_accepted" value="{{ old('papers_read_accepted', $otherDetail->papers_read_accepted) }}" step="1">
                @if($errors->has('papers_read_accepted'))
                    <span class="text-danger">{{ $errors->first('papers_read_accepted') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_read_accepted_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eca_university_administration">{{ trans('cruds.otherDetail.fields.eca_university_administration') }}</label>
                <input class="form-control {{ $errors->has('eca_university_administration') ? 'is-invalid' : '' }}" type="text" name="eca_university_administration" id="eca_university_administration" value="{{ old('eca_university_administration', $otherDetail->eca_university_administration) }}">
                @if($errors->has('eca_university_administration'))
                    <span class="text-danger">{{ $errors->first('eca_university_administration') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_university_administration_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eca_student">{{ trans('cruds.otherDetail.fields.eca_student') }}</label>
                <input class="form-control {{ $errors->has('eca_student') ? 'is-invalid' : '' }}" type="text" name="eca_student" id="eca_student" value="{{ old('eca_student', $otherDetail->eca_student) }}">
                @if($errors->has('eca_student'))
                    <span class="text-danger">{{ $errors->first('eca_student') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_student_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eca_residential_student">{{ trans('cruds.otherDetail.fields.eca_residential_student') }}</label>
                <input class="form-control {{ $errors->has('eca_residential_student') ? 'is-invalid' : '' }}" type="text" name="eca_residential_student" id="eca_residential_student" value="{{ old('eca_residential_student', $otherDetail->eca_residential_student) }}">
                @if($errors->has('eca_residential_student'))
                    <span class="text-danger">{{ $errors->first('eca_residential_student') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_residential_student_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eca_cultural">{{ trans('cruds.otherDetail.fields.eca_cultural') }}</label>
                <input class="form-control {{ $errors->has('eca_cultural') ? 'is-invalid' : '' }}" type="text" name="eca_cultural" id="eca_cultural" value="{{ old('eca_cultural', $otherDetail->eca_cultural) }}">
                @if($errors->has('eca_cultural'))
                    <span class="text-danger">{{ $errors->first('eca_cultural') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_cultural_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="relevant_work">{{ trans('cruds.otherDetail.fields.relevant_work') }}</label>
                <textarea class="form-control {{ $errors->has('relevant_work') ? 'is-invalid' : '' }}" name="relevant_work" id="relevant_work">{{ old('relevant_work', $otherDetail->relevant_work) }}</textarea>
                @if($errors->has('relevant_work'))
                    <span class="text-danger">{{ $errors->first('relevant_work') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.relevant_work_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="previous_applications">{{ trans('cruds.otherDetail.fields.previous_applications') }}</label>
                <input class="form-control {{ $errors->has('previous_applications') ? 'is-invalid' : '' }}" type="text" name="previous_applications" id="previous_applications" value="{{ old('previous_applications', $otherDetail->previous_applications) }}">
                @if($errors->has('previous_applications'))
                    <span class="text-danger">{{ $errors->first('previous_applications') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.previous_applications_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="testimonial_1">{{ trans('cruds.otherDetail.fields.testimonial_1') }}</label>
                <input class="form-control {{ $errors->has('testimonial_1') ? 'is-invalid' : '' }}" type="text" name="testimonial_1" id="testimonial_1" value="{{ old('testimonial_1', $otherDetail->testimonial_1) }}">
                @if($errors->has('testimonial_1'))
                    <span class="text-danger">{{ $errors->first('testimonial_1') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.testimonial_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="testimonial_2">{{ trans('cruds.otherDetail.fields.testimonial_2') }}</label>
                <input class="form-control {{ $errors->has('testimonial_2') ? 'is-invalid' : '' }}" type="text" name="testimonial_2" id="testimonial_2" value="{{ old('testimonial_2', $otherDetail->testimonial_2) }}">
                @if($errors->has('testimonial_2'))
                    <span class="text-danger">{{ $errors->first('testimonial_2') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.testimonial_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="testimonial_3">{{ trans('cruds.otherDetail.fields.testimonial_3') }}</label>
                <input class="form-control {{ $errors->has('testimonial_3') ? 'is-invalid' : '' }}" type="text" name="testimonial_3" id="testimonial_3" value="{{ old('testimonial_3', $otherDetail->testimonial_3) }}">
                @if($errors->has('testimonial_3'))
                    <span class="text-danger">{{ $errors->first('testimonial_3') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.testimonial_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark_essential_qualification">{{ trans('cruds.otherDetail.fields.remark_essential_qualification') }}</label>
                <textarea class="form-control {{ $errors->has('remark_essential_qualification') ? 'is-invalid' : '' }}" name="remark_essential_qualification" id="remark_essential_qualification">{{ old('remark_essential_qualification', $otherDetail->remark_essential_qualification) }}</textarea>
                @if($errors->has('remark_essential_qualification'))
                    <span class="text-danger">{{ $errors->first('remark_essential_qualification') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_essential_qualification_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark_essential_qualification_document">{{ trans('cruds.otherDetail.fields.remark_essential_qualification_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('remark_essential_qualification_document') ? 'is-invalid' : '' }}" id="remark_essential_qualification_document-dropzone">
                </div>
                @if($errors->has('remark_essential_qualification_document'))
                    <span class="text-danger">{{ $errors->first('remark_essential_qualification_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_essential_qualification_document_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark_desirable_qualification">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification') }}</label>
                <textarea class="form-control {{ $errors->has('remark_desirable_qualification') ? 'is-invalid' : '' }}" name="remark_desirable_qualification" id="remark_desirable_qualification">{{ old('remark_desirable_qualification', $otherDetail->remark_desirable_qualification) }}</textarea>
                @if($errors->has('remark_desirable_qualification'))
                    <span class="text-danger">{{ $errors->first('remark_desirable_qualification') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark_desirable_qualification_document">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification_document') }}</label>
                <div class="needsclick dropzone {{ $errors->has('remark_desirable_qualification_document') ? 'is-invalid' : '' }}" id="remark_desirable_qualification_document-dropzone">
                </div>
                @if($errors->has('remark_desirable_qualification_document'))
                    <span class="text-danger">{{ $errors->first('remark_desirable_qualification_document') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification_document_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.remarkEssentialQualificationDocumentDropzone = {
    url: '{{ route('admin.other-details.storeMedia') }}',
    maxFilesize: 5, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5
    },
    success: function (file, response) {
      $('form').find('input[name="remark_essential_qualification_document"]').remove()
      $('form').append('<input type="hidden" name="remark_essential_qualification_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="remark_essential_qualification_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($otherDetail) && $otherDetail->remark_essential_qualification_document)
      var file = {!! json_encode($otherDetail->remark_essential_qualification_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="remark_essential_qualification_document" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    Dropzone.options.remarkDesirableQualificationDocumentDropzone = {
    url: '{{ route('admin.other-details.storeMedia') }}',
    maxFilesize: 5, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5
    },
    success: function (file, response) {
      $('form').find('input[name="remark_desirable_qualification_document"]').remove()
      $('form').append('<input type="hidden" name="remark_desirable_qualification_document" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="remark_desirable_qualification_document"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($otherDetail) && $otherDetail->remark_desirable_qualification_document)
      var file = {!! json_encode($otherDetail->remark_desirable_qualification_document) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="remark_desirable_qualification_document" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection