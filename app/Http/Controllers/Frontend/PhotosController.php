<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendPhotosController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $user = Auth::user();
        $photoRecord = Photo::firstOrCreate(['user_id' => $user->id]);

        $photoRecord->load('photo', 'signature');

        return view('frontend.photos.index', compact('photoRecord'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $photoRecord = Photo::firstOrCreate(['user_id' => $user->id]);

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($photoRecord->photo) {
                $photoRecord->photo->delete();
            }
            $photoRecord->addMedia($request->file('photo'))->toMediaCollection('photo');
        }

        if ($request->hasFile('signature')) {
            $request->validate([
                'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($photoRecord->signature) {
                $photoRecord->signature->delete();
            }
            $photoRecord->addMedia($request->file('signature'))->toMediaCollection('signature');
        }

        return redirect()->route('frontend.photos.index')->with('status', 'Documents uploaded successfully.');
    }
}
