@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.otherDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.other-details.update", [$otherDetail->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.otherDetail.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $otherDetail->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="fellowship_undergraduate">{{ trans('cruds.otherDetail.fields.fellowship_undergraduate') }}</label>
                            <input class="form-control" type="text" name="fellowship_undergraduate" id="fellowship_undergraduate" value="{{ old('fellowship_undergraduate', $otherDetail->fellowship_undergraduate) }}" required>
                            @if($errors->has('fellowship_undergraduate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fellowship_undergraduate') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.fellowship_undergraduate_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fellowship_graduate">{{ trans('cruds.otherDetail.fields.fellowship_graduate') }}</label>
                            <input class="form-control" type="text" name="fellowship_graduate" id="fellowship_graduate" value="{{ old('fellowship_graduate', $otherDetail->fellowship_graduate) }}">
                            @if($errors->has('fellowship_graduate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fellowship_graduate') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.fellowship_graduate_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fellowship_postgraduate">{{ trans('cruds.otherDetail.fields.fellowship_postgraduate') }}</label>
                            <input class="form-control" type="text" name="fellowship_postgraduate" id="fellowship_postgraduate" value="{{ old('fellowship_postgraduate', $otherDetail->fellowship_postgraduate) }}">
                            @if($errors->has('fellowship_postgraduate'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fellowship_postgraduate') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.fellowship_postgraduate_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="phd_thesis_title">{{ trans('cruds.otherDetail.fields.phd_thesis_title') }}</label>
                            <input class="form-control" type="text" name="phd_thesis_title" id="phd_thesis_title" value="{{ old('phd_thesis_title', $otherDetail->phd_thesis_title) }}">
                            @if($errors->has('phd_thesis_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('phd_thesis_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.phd_thesis_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_phd_awarded">{{ trans('cruds.otherDetail.fields.research_phd_awarded') }}</label>
                            <input class="form-control" type="number" name="research_phd_awarded" id="research_phd_awarded" value="{{ old('research_phd_awarded', $otherDetail->research_phd_awarded) }}" step="1">
                            @if($errors->has('research_phd_awarded'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_phd_awarded') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_phd_awarded_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_phd_thesis">{{ trans('cruds.otherDetail.fields.research_phd_thesis') }}</label>
                            <input class="form-control" type="number" name="research_phd_thesis" id="research_phd_thesis" value="{{ old('research_phd_thesis', $otherDetail->research_phd_thesis) }}" step="1">
                            @if($errors->has('research_phd_thesis'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_phd_thesis') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_phd_thesis_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_phd_total_scholars">{{ trans('cruds.otherDetail.fields.research_phd_total_scholars') }}</label>
                            <input class="form-control" type="number" name="research_phd_total_scholars" id="research_phd_total_scholars" value="{{ old('research_phd_total_scholars', $otherDetail->research_phd_total_scholars) }}" step="1">
                            @if($errors->has('research_phd_total_scholars'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_phd_total_scholars') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_phd_total_scholars_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_mphil_awarded">{{ trans('cruds.otherDetail.fields.research_mphil_awarded') }}</label>
                            <input class="form-control" type="number" name="research_mphil_awarded" id="research_mphil_awarded" value="{{ old('research_mphil_awarded', $otherDetail->research_mphil_awarded) }}" step="1">
                            @if($errors->has('research_mphil_awarded'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_mphil_awarded') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_mphil_awarded_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_mphil_thesis">{{ trans('cruds.otherDetail.fields.research_mphil_thesis') }}</label>
                            <input class="form-control" type="number" name="research_mphil_thesis" id="research_mphil_thesis" value="{{ old('research_mphil_thesis', $otherDetail->research_mphil_thesis) }}" step="1">
                            @if($errors->has('research_mphil_thesis'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_mphil_thesis') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_mphil_thesis_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_mphil_total_scholars">{{ trans('cruds.otherDetail.fields.research_mphil_total_scholars') }}</label>
                            <input class="form-control" type="number" name="research_mphil_total_scholars" id="research_mphil_total_scholars" value="{{ old('research_mphil_total_scholars', $otherDetail->research_mphil_total_scholars) }}" step="1">
                            @if($errors->has('research_mphil_total_scholars'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_mphil_total_scholars') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_mphil_total_scholars_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_other_awarded">{{ trans('cruds.otherDetail.fields.research_other_awarded') }}</label>
                            <input class="form-control" type="number" name="research_other_awarded" id="research_other_awarded" value="{{ old('research_other_awarded', $otherDetail->research_other_awarded) }}" step="1">
                            @if($errors->has('research_other_awarded'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_other_awarded') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_other_awarded_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_other_thesis">{{ trans('cruds.otherDetail.fields.research_other_thesis') }}</label>
                            <input class="form-control" type="number" name="research_other_thesis" id="research_other_thesis" value="{{ old('research_other_thesis', $otherDetail->research_other_thesis) }}" step="1">
                            @if($errors->has('research_other_thesis'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_other_thesis') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_other_thesis_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="research_other_total_scholars">{{ trans('cruds.otherDetail.fields.research_other_total_scholars') }}</label>
                            <input class="form-control" type="number" name="research_other_total_scholars" id="research_other_total_scholars" value="{{ old('research_other_total_scholars', $otherDetail->research_other_total_scholars) }}" step="1">
                            @if($errors->has('research_other_total_scholars'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('research_other_total_scholars') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.research_other_total_scholars_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eminent_scholar">{{ trans('cruds.otherDetail.fields.eminent_scholar') }}</label>
                            <textarea class="form-control" name="eminent_scholar" id="eminent_scholar">{{ old('eminent_scholar', $otherDetail->eminent_scholar) }}</textarea>
                            @if($errors->has('eminent_scholar'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eminent_scholar') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.eminent_scholar_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="contribution_to_knowledge">{{ trans('cruds.otherDetail.fields.contribution_to_knowledge') }}</label>
                            <textarea class="form-control" name="contribution_to_knowledge" id="contribution_to_knowledge">{{ old('contribution_to_knowledge', $otherDetail->contribution_to_knowledge) }}</textarea>
                            @if($errors->has('contribution_to_knowledge'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('contribution_to_knowledge') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.contribution_to_knowledge_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="engaged_in_research">{{ trans('cruds.otherDetail.fields.engaged_in_research') }}</label>
                            <textarea class="form-control" name="engaged_in_research" id="engaged_in_research">{{ old('engaged_in_research', $otherDetail->engaged_in_research) }}</textarea>
                            @if($errors->has('engaged_in_research'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('engaged_in_research') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.engaged_in_research_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="industry_experience">{{ trans('cruds.otherDetail.fields.industry_experience') }}</label>
                            <textarea class="form-control" name="industry_experience" id="industry_experience">{{ old('industry_experience', $otherDetail->industry_experience) }}</textarea>
                            @if($errors->has('industry_experience'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('industry_experience') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.industry_experience_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_pay_level">{{ trans('cruds.otherDetail.fields.current_pay_level') }}</label>
                            <input class="form-control" type="text" name="current_pay_level" id="current_pay_level" value="{{ old('current_pay_level', $otherDetail->current_pay_level) }}">
                            @if($errors->has('current_pay_level'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_pay_level') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_pay_level_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_pay_range">{{ trans('cruds.otherDetail.fields.current_pay_range') }}</label>
                            <input class="form-control" type="text" name="current_pay_range" id="current_pay_range" value="{{ old('current_pay_range', $otherDetail->current_pay_range) }}">
                            @if($errors->has('current_pay_range'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_pay_range') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_pay_range_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_basic_pay">{{ trans('cruds.otherDetail.fields.current_basic_pay') }}</label>
                            <input class="form-control" type="text" name="current_basic_pay" id="current_basic_pay" value="{{ old('current_basic_pay', $otherDetail->current_basic_pay) }}">
                            @if($errors->has('current_basic_pay'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_basic_pay') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_basic_pay_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_pay_band">{{ trans('cruds.otherDetail.fields.current_pay_band') }}</label>
                            <input class="form-control" type="text" name="current_pay_band" id="current_pay_band" value="{{ old('current_pay_band', $otherDetail->current_pay_band) }}">
                            @if($errors->has('current_pay_band'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_pay_band') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_pay_band_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_grade_pay">{{ trans('cruds.otherDetail.fields.current_grade_pay') }}</label>
                            <input class="form-control" type="text" name="current_grade_pay" id="current_grade_pay" value="{{ old('current_grade_pay', $otherDetail->current_grade_pay) }}">
                            @if($errors->has('current_grade_pay'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_grade_pay') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_grade_pay_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_basic_pay_old">{{ trans('cruds.otherDetail.fields.current_basic_pay_old') }}</label>
                            <input class="form-control" type="text" name="current_basic_pay_old" id="current_basic_pay_old" value="{{ old('current_basic_pay_old', $otherDetail->current_basic_pay_old) }}">
                            @if($errors->has('current_basic_pay_old'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_basic_pay_old') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_basic_pay_old_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_allowances">{{ trans('cruds.otherDetail.fields.current_allowances') }}</label>
                            <input class="form-control" type="text" name="current_allowances" id="current_allowances" value="{{ old('current_allowances', $otherDetail->current_allowances) }}">
                            @if($errors->has('current_allowances'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_allowances') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_allowances_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="current_allowances_total">{{ trans('cruds.otherDetail.fields.current_allowances_total') }}</label>
                            <input class="form-control" type="text" name="current_allowances_total" id="current_allowances_total" value="{{ old('current_allowances_total', $otherDetail->current_allowances_total) }}">
                            @if($errors->has('current_allowances_total'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('current_allowances_total') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.current_allowances_total_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="increment_date">{{ trans('cruds.otherDetail.fields.increment_date') }}</label>
                            <input class="form-control date" type="text" name="increment_date" id="increment_date" value="{{ old('increment_date', $otherDetail->increment_date) }}">
                            @if($errors->has('increment_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('increment_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.increment_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="minimum_initial_pay">{{ trans('cruds.otherDetail.fields.minimum_initial_pay') }}</label>
                            <textarea class="form-control" name="minimum_initial_pay" id="minimum_initial_pay">{{ old('minimum_initial_pay', $otherDetail->minimum_initial_pay) }}</textarea>
                            @if($errors->has('minimum_initial_pay'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('minimum_initial_pay') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.minimum_initial_pay_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="joining_time">{{ trans('cruds.otherDetail.fields.joining_time') }}</label>
                            <input class="form-control" type="text" name="joining_time" id="joining_time" value="{{ old('joining_time', $otherDetail->joining_time) }}">
                            @if($errors->has('joining_time'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('joining_time') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.joining_time_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="books_published">{{ trans('cruds.otherDetail.fields.books_published') }}</label>
                            <input class="form-control" type="number" name="books_published" id="books_published" value="{{ old('books_published', $otherDetail->books_published) }}" step="1">
                            @if($errors->has('books_published'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('books_published') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.books_published_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="books_accepted">{{ trans('cruds.otherDetail.fields.books_accepted') }}</label>
                            <input class="form-control" type="number" name="books_accepted" id="books_accepted" value="{{ old('books_accepted', $otherDetail->books_accepted) }}" step="1">
                            @if($errors->has('books_accepted'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('books_accepted') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.books_accepted_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="papers_published">{{ trans('cruds.otherDetail.fields.papers_published') }}</label>
                            <input class="form-control" type="number" name="papers_published" id="papers_published" value="{{ old('papers_published', $otherDetail->papers_published) }}" step="1">
                            @if($errors->has('papers_published'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('papers_published') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_published_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="papers_accepted">{{ trans('cruds.otherDetail.fields.papers_accepted') }}</label>
                            <input class="form-control" type="number" name="papers_accepted" id="papers_accepted" value="{{ old('papers_accepted', $otherDetail->papers_accepted) }}" step="1">
                            @if($errors->has('papers_accepted'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('papers_accepted') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_accepted_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="articles_published">{{ trans('cruds.otherDetail.fields.articles_published') }}</label>
                            <input class="form-control" type="number" name="articles_published" id="articles_published" value="{{ old('articles_published', $otherDetail->articles_published) }}" step="1">
                            @if($errors->has('articles_published'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('articles_published') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.articles_published_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="articles_accepted">{{ trans('cruds.otherDetail.fields.articles_accepted') }}</label>
                            <input class="form-control" type="number" name="articles_accepted" id="articles_accepted" value="{{ old('articles_accepted', $otherDetail->articles_accepted) }}" step="1">
                            @if($errors->has('articles_accepted'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('articles_accepted') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.articles_accepted_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="papers_read_published">{{ trans('cruds.otherDetail.fields.papers_read_published') }}</label>
                            <input class="form-control" type="number" name="papers_read_published" id="papers_read_published" value="{{ old('papers_read_published', $otherDetail->papers_read_published) }}" step="1">
                            @if($errors->has('papers_read_published'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('papers_read_published') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_read_published_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="papers_read_accepted">{{ trans('cruds.otherDetail.fields.papers_read_accepted') }}</label>
                            <input class="form-control" type="number" name="papers_read_accepted" id="papers_read_accepted" value="{{ old('papers_read_accepted', $otherDetail->papers_read_accepted) }}" step="1">
                            @if($errors->has('papers_read_accepted'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('papers_read_accepted') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.papers_read_accepted_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eca_university_administration">{{ trans('cruds.otherDetail.fields.eca_university_administration') }}</label>
                            <input class="form-control" type="text" name="eca_university_administration" id="eca_university_administration" value="{{ old('eca_university_administration', $otherDetail->eca_university_administration) }}">
                            @if($errors->has('eca_university_administration'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eca_university_administration') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_university_administration_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eca_student">{{ trans('cruds.otherDetail.fields.eca_student') }}</label>
                            <input class="form-control" type="text" name="eca_student" id="eca_student" value="{{ old('eca_student', $otherDetail->eca_student) }}">
                            @if($errors->has('eca_student'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eca_student') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_student_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eca_residential_student">{{ trans('cruds.otherDetail.fields.eca_residential_student') }}</label>
                            <input class="form-control" type="text" name="eca_residential_student" id="eca_residential_student" value="{{ old('eca_residential_student', $otherDetail->eca_residential_student) }}">
                            @if($errors->has('eca_residential_student'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eca_residential_student') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_residential_student_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eca_cultural">{{ trans('cruds.otherDetail.fields.eca_cultural') }}</label>
                            <input class="form-control" type="text" name="eca_cultural" id="eca_cultural" value="{{ old('eca_cultural', $otherDetail->eca_cultural) }}">
                            @if($errors->has('eca_cultural'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eca_cultural') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.eca_cultural_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="relevant_work">{{ trans('cruds.otherDetail.fields.relevant_work') }}</label>
                            <textarea class="form-control" name="relevant_work" id="relevant_work">{{ old('relevant_work', $otherDetail->relevant_work) }}</textarea>
                            @if($errors->has('relevant_work'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('relevant_work') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.relevant_work_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="previous_applications">{{ trans('cruds.otherDetail.fields.previous_applications') }}</label>
                            <input class="form-control" type="text" name="previous_applications" id="previous_applications" value="{{ old('previous_applications', $otherDetail->previous_applications) }}">
                            @if($errors->has('previous_applications'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('previous_applications') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.previous_applications_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_1">{{ trans('cruds.otherDetail.fields.testimonial_1') }}</label>
                            <input class="form-control" type="text" name="testimonial_1" id="testimonial_1" value="{{ old('testimonial_1', $otherDetail->testimonial_1) }}">
                            @if($errors->has('testimonial_1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('testimonial_1') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.testimonial_1_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_2">{{ trans('cruds.otherDetail.fields.testimonial_2') }}</label>
                            <input class="form-control" type="text" name="testimonial_2" id="testimonial_2" value="{{ old('testimonial_2', $otherDetail->testimonial_2) }}">
                            @if($errors->has('testimonial_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('testimonial_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.testimonial_2_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="testimonial_3">{{ trans('cruds.otherDetail.fields.testimonial_3') }}</label>
                            <input class="form-control" type="text" name="testimonial_3" id="testimonial_3" value="{{ old('testimonial_3', $otherDetail->testimonial_3) }}">
                            @if($errors->has('testimonial_3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('testimonial_3') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.testimonial_3_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remark_essential_qualification">{{ trans('cruds.otherDetail.fields.remark_essential_qualification') }}</label>
                            <textarea class="form-control" name="remark_essential_qualification" id="remark_essential_qualification">{{ old('remark_essential_qualification', $otherDetail->remark_essential_qualification) }}</textarea>
                            @if($errors->has('remark_essential_qualification'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remark_essential_qualification') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_essential_qualification_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remark_essential_qualification_document">{{ trans('cruds.otherDetail.fields.remark_essential_qualification_document') }}</label>
                            <div class="needsclick dropzone" id="remark_essential_qualification_document-dropzone">
                            </div>
                            @if($errors->has('remark_essential_qualification_document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remark_essential_qualification_document') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_essential_qualification_document_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remark_desirable_qualification">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification') }}</label>
                            <textarea class="form-control" name="remark_desirable_qualification" id="remark_desirable_qualification">{{ old('remark_desirable_qualification', $otherDetail->remark_desirable_qualification) }}</textarea>
                            @if($errors->has('remark_desirable_qualification'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remark_desirable_qualification') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remark_desirable_qualification_document">{{ trans('cruds.otherDetail.fields.remark_desirable_qualification_document') }}</label>
                            <div class="needsclick dropzone" id="remark_desirable_qualification_document-dropzone">
                            </div>
                            @if($errors->has('remark_desirable_qualification_document'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remark_desirable_qualification_document') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.remarkEssentialQualificationDocumentDropzone = {
    url: '{{ route('frontend.other-details.storeMedia') }}',
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
    url: '{{ route('frontend.other-details.storeMedia') }}',
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