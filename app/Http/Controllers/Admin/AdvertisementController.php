<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Recruitment\IssueCorrigendum;
use App\Domain\Recruitment\PublishAdvertisement;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdvertisementRequest;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class AdvertisementController extends Controller
{
    public function __construct(
        private readonly PublishAdvertisement $publish,
        private readonly IssueCorrigendum $corrigendum,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Advertisement::class);

        return view('admin.advertisements.index', [
            'advertisements' => Advertisement::query()
                // Scoped, never a bare query: a Dean's-office user sees their
                // faculty's local advertisements and nothing else.
                ->visibleTo($request->user())
                ->withCount('posts')
                ->latest('id')
                ->paginate(50),
        ]);
    }

    public function show(Advertisement $advertisement): View
    {
        $this->authorize('view', $advertisement);

        return view('admin.advertisements.show', [
            'advertisement' => $advertisement->load(['posts', 'corrigenda']),
        ]);
    }

    public function store(StoreAdvertisementRequest $request): RedirectResponse
    {
        $this->authorize('create', Advertisement::class);

        $advertisement = Advertisement::query()->create($request->validated() + [
            'slug' => Str::slug($request->string('title').'-'.$request->string('advertisement_no')),
            'added_by_id' => $request->user()?->getKey(),
        ]);

        return redirect()
            ->route('admin.advertisements.show', $advertisement)
            ->with('status', __('recruitment.created'));
    }

    public function publish(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $this->authorize('publish', $advertisement);

        try {
            $this->publish->handle($advertisement, $request->user());
        } catch (RuntimeException $e) {
            return back()->withErrors(['publish' => $e->getMessage()]);
        }

        return back()->with('status', __('recruitment.published'));
    }

    public function corrigendum(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $this->authorize('issueCorrigendum', $advertisement);

        $request->validate([
            'description' => ['required', 'string', 'max:2000'],
            'default_closing_date' => ['nullable', 'date'],
        ]);

        $changes = array_filter([
            'default_closing_date' => $request->input('default_closing_date'),
        ], static fn (mixed $value): bool => $value !== null);

        try {
            $this->corrigendum->handle(
                $advertisement,
                $request->user(),
                (string) $request->string('description'),
                $changes,
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['corrigendum' => $e->getMessage()]);
        }

        return back()->with('status', __('recruitment.corrigendum_issued'));
    }
}
