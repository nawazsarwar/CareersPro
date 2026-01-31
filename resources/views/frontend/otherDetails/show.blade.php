@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.otherDetail.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.other-details.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.fellowship_undergraduate') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->fellowship_undergraduate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.fellowship_graduate') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->fellowship_graduate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.fellowship_postgraduate') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->fellowship_postgraduate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.phd_thesis_title') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->phd_thesis_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_phd_awarded') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_phd_awarded }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_phd_thesis') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_phd_thesis }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_phd_total_scholars') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_phd_total_scholars }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_mphil_awarded') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_mphil_awarded }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_mphil_thesis') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_mphil_thesis }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_mphil_total_scholars') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_mphil_total_scholars }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_other_awarded') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_other_awarded }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_other_thesis') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_other_thesis }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.research_other_total_scholars') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->research_other_total_scholars }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eminent_scholar') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->eminent_scholar }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.contribution_to_knowledge') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->contribution_to_knowledge }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.engaged_in_research') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->engaged_in_research }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.industry_experience') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->industry_experience }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_pay_level') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_pay_level }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_pay_range') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_pay_range }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_basic_pay') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_basic_pay }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_pay_band') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_pay_band }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_grade_pay') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_grade_pay }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_basic_pay_old') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_basic_pay_old }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_allowances') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_allowances }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.current_allowances_total') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->current_allowances_total }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.increment_date') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->increment_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.minimum_initial_pay') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->minimum_initial_pay }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.joining_time') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->joining_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.books_published') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->books_published }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.books_accepted') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->books_accepted }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_published') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->papers_published }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_accepted') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->papers_accepted }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.articles_published') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->articles_published }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.articles_accepted') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->articles_accepted }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_read_published') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->papers_read_published }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.papers_read_accepted') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->papers_read_accepted }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_university_administration') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->eca_university_administration }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_student') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->eca_student }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_residential_student') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->eca_residential_student }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.eca_cultural') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->eca_cultural }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.relevant_work') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->relevant_work }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.previous_applications') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->previous_applications }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.testimonial_1') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->testimonial_1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.testimonial_2') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->testimonial_2 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.testimonial_3') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->testimonial_3 }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_essential_qualification') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->remark_essential_qualification }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_essential_qualification_document') }}
                                    </th>
                                    <td>
                                        @if($otherDetail->remark_essential_qualification_document)
                                            <a href="{{ $otherDetail->remark_essential_qualification_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_desirable_qualification') }}
                                    </th>
                                    <td>
                                        {{ $otherDetail->remark_desirable_qualification }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.otherDetail.fields.remark_desirable_qualification_document') }}
                                    </th>
                                    <td>
                                        @if($otherDetail->remark_desirable_qualification_document)
                                            <a href="{{ $otherDetail->remark_desirable_qualification_document->getUrl() }}" target="_blank">
                                                {{ trans('global.view_file') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.other-details.index') }}">
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