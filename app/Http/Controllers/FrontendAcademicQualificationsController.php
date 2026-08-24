<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAcademicQualificationRequest;
use App\Http\Requests\UpdateAcademicQualificationRequest;
use App\Models\AcademicQualification;
use App\Models\Board;
use App\Models\QualificationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendAcademicQualificationsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $user = Auth::user();
        $qualifications = AcademicQualification::where('user_id', $user->id)
            ->with(['qualification_level', 'board'])
            ->get();

        $levels = QualificationLevel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $boards = Board::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.academicQualifications.index', compact('qualifications', 'levels', 'boards'));
    }

    public function store(StoreAcademicQualificationRequest $request)
    {
        $user = Auth::user();

        // Enforce sequence logic (simplified for stub: e.g., if adding PG, check if UG exists)
        $level = QualificationLevel::find($request->qualification_level_id);
        if ($level && $level->name == 'Post Graduation') {
            $hasUg = AcademicQualification::where('user_id', $user->id)
                ->whereHas('qualification_level', function($q) {
                    $q->where('name', 'Graduation');
                })->exists();
            if (!$hasUg) {
                return back()->withErrors(['qualification_level_id' => 'You must add Graduation before adding Post Graduation.'])->withInput();
            }
        }

        $data = $request->all();
        $data['user_id'] = $user->id;

        $qualification = AcademicQualification::create($data);

        if ($request->input('certificate', false)) {
            $qualification->addMedia(storage_path('tmp/uploads/' . basename($request->input('certificate'))))->toMediaCollection('certificate');
        }

        return redirect()->route('frontend.academic-qualifications.index')->with('status', 'Qualification added successfully.');
    }
}
