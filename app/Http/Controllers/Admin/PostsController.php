<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Advertisement;
use App\Models\Post;
use App\Models\PostType;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Post::with(['advertisement', 'posttype', 'added_by'])->select(sprintf('%s.*', (new Post)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'post_show';
                $editGate      = 'post_edit';
                $deleteGate    = 'post_delete';
                $crudRoutePart = 'posts';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('advertisement_title', function ($row) {
                return $row->advertisement ? $row->advertisement->title : '';
            });

            $table->addColumn('posttype_name', function ($row) {
                return $row->posttype ? $row->posttype->name : '';
            });

            $table->editColumn('serial_no', function ($row) {
                return $row->serial_no ? $row->serial_no : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('subject', function ($row) {
                return $row->subject ? $row->subject : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('vacancies', function ($row) {
                return $row->vacancies ? $row->vacancies : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });
            $table->editColumn('pay_level', function ($row) {
                return $row->pay_level ? $row->pay_level : '';
            });
            $table->editColumn('pay_range', function ($row) {
                return $row->pay_range ? $row->pay_range : '';
            });
            $table->editColumn('fee', function ($row) {
                return $row->fee ? $row->fee : '';
            });

            $table->editColumn('withdrawn', function ($row) {
                return $row->withdrawn ? $row->withdrawn : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? $row->status : '';
            });
            $table->editColumn('remarks', function ($row) {
                return $row->remarks ? $row->remarks : '';
            });
            $table->addColumn('added_by_name', function ($row) {
                return $row->added_by ? $row->added_by->name : '';
            });

            $table->editColumn('test_reporting_time', function ($row) {
                return $row->test_reporting_time ? $row->test_reporting_time : '';
            });
            $table->editColumn('gate_closing_time', function ($row) {
                return $row->gate_closing_time ? $row->gate_closing_time : '';
            });
            $table->editColumn('scheduled_test_start', function ($row) {
                return $row->scheduled_test_start ? $row->scheduled_test_start : '';
            });
            $table->editColumn('test_duration', function ($row) {
                return $row->test_duration ? $row->test_duration : '';
            });

            $table->editColumn('interview_time', function ($row) {
                return $row->interview_time ? $row->interview_time : '';
            });
            $table->editColumn('interview_venue', function ($row) {
                return $row->interview_venue ? $row->interview_venue : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'advertisement', 'posttype', 'added_by']);

            return $table->make(true);
        }

        $advertisements = Advertisement::get();
        $post_types     = PostType::get();
        $users          = User::get();

        return view('admin.posts.index', compact('advertisements', 'post_types', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $posttypes = PostType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.posts.create', compact('added_bies', 'advertisements', 'posttypes'));
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->all());

        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $advertisements = Advertisement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $posttypes = PostType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $added_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $post->load('advertisement', 'posttype', 'added_by');

        return view('admin.posts.edit', compact('added_bies', 'advertisements', 'post', 'posttypes'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->all());

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->load('advertisement', 'posttype', 'added_by');

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostRequest $request)
    {
        $posts = Post::find(request('ids'));

        foreach ($posts as $post) {
            $post->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
