<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domain\Application\AssertCompleteness;
use App\Domain\Application\PreflightEligibility;
use App\Domain\Application\SubmitApplication;
use App\Domain\Public\VacancyVisibility;
use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

class ApplicationController extends Controller
{
    public function __construct(
        private readonly VacancyVisibility $visibility,
        private readonly PreflightEligibility $preflight,
        private readonly AssertCompleteness $completeness,
        private readonly SubmitApplication $submit,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Application::class);

        return view('frontend.applications.index', [
            // Through the scope, never a bare query: engineering-standards
            // §2.2 rule 2, and the reason a candidate cannot reach another's.
            'applications' => Application::query()
                ->visibleTo($request->user())
                ->with(['post', 'eligibilityDecisions'])
                ->latest('id')
                ->paginate(25),
        ]);
    }

    public function show(Request $request, Application $application): View
    {
        $this->authorize('view', $application);

        return view('frontend.applications.show', [
            'application' => $application->load(['post', 'eligibilityDecisions', 'deficiencies']),
        ]);
    }

    /**
     * The pre-payment check (M05 §3).
     *
     * The legacy portal took the fee first and evaluated eligibility
     * afterwards, so a candidate who was never eligible paid to find out.
     */
    public function create(Request $request, string $slug): View
    {
        $this->authorize('create', Application::class);

        $post = $this->visibility->openQuery()->where('slug', $slug)->firstOrFail();
        $user = $request->user();

        return view('frontend.applications.create', [
            'post' => $post,
            'preflight' => $this->preflight->check($user, $post),
            'missing' => $this->completeness->check($user, $post),
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        $this->authorize('create', Application::class);

        $post = $this->visibility->openQuery()->where('slug', $slug)->firstOrFail();

        $request->validate([
            'applied_under_category' => ['nullable', 'string', 'max:16'],
            'confirm' => ['accepted'],
        ]);

        try {
            $application = $this->submit->handle(
                $request->user(),
                $post,
                $request->input('applied_under_category'),
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['submit' => $e->getMessage()]);
        }

        return redirect()
            ->route('frontend.applications.show', $application)
            ->with('status', __('application.submitted'));
    }
}
