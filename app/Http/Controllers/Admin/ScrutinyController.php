<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Eligibility\DecideGate;
use App\Domain\Scrutiny\BuildQueue;
use App\Domain\Scrutiny\OpenScrutiny;
use App\Domain\Scrutiny\RaiseDeficiency;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DecideGateRequest;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;

/**
 * The scrutiny workbench (M18).
 *
 * DR-021's interaction pattern: a JSON endpoint plus a plain form fallback on
 * the same route. Alpine intercepts the submit and updates the row in place;
 * with JavaScript off the same form posts and redirects back. One route, one
 * Form Request, one policy check, one audit entry -- two representations.
 */
class ScrutinyController extends Controller
{
    public function __construct(
        private readonly BuildQueue $queue,
        private readonly OpenScrutiny $open,
        private readonly DecideGate $decideGate,
        private readonly RaiseDeficiency $raiseDeficiency,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Application::class);

        return view('admin.scrutiny.index', [
            'applications' => $this->queue
                ->for($request->user(), $request->only(['post_id', 'state', 'scrutiny']))
                ->paginate(100)
                ->withQueryString(),
            'filters' => $request->only(['post_id', 'state', 'scrutiny']),
        ]);
    }

    public function show(Request $request, Application $application): View
    {
        $this->authorize('scrutinise', $application);

        return view('admin.scrutiny.show', [
            'application' => $application->load([
                'user.profile', 'post.postType', 'eligibilityDecisions', 'deficiencies',
            ]),
        ]);
    }

    public function open(Request $request, Application $application): RedirectResponse
    {
        $this->authorize('scrutinise', $application);

        try {
            $this->open->handle($application, $request->user());
        } catch (RuntimeException $e) {
            return back()->withErrors(['scrutiny' => $e->getMessage()]);
        }

        return back()->with('status', __('scrutiny.opened'));
    }

    public function decide(DecideGateRequest $request, Application $application): RedirectResponse|JsonResponse
    {
        $this->authorize('decideGate', $application);

        try {
            $this->decideGate->handle(
                $application,
                EligibilityGate::from((string) $request->string('gate')),
                GateDecision::tryFrom((string) $request->string('decision')),
                $request->input('remark'),
                $request->user(),
            );
        } catch (RuntimeException $e) {
            return $request->expectsJson()
                ? response()->json(['message' => $e->getMessage()], 422)
                : back()->withErrors(['gate' => $e->getMessage()]);
        }

        $decision = $application->refresh()->eligibilityDecisions
            ->firstWhere('gate.value', $request->string('gate')->toString());

        return $request->expectsJson()
            ? response()->json([
                'gate' => $decision?->gate->value,
                'decision' => $decision?->decision?->value,
                'label' => GateDecision::label($decision?->decision),
                'remark' => $decision?->remark,
            ])
            : back()->with('status', __('scrutiny.decided'));
    }

    public function deficiency(Request $request, Application $application): RedirectResponse
    {
        $this->authorize('raiseDeficiency', $application);

        $request->validate([
            'description' => ['required', 'string', 'max:2000'],
            'field_reference' => ['nullable', 'string', 'max:191'],
        ]);

        try {
            $this->raiseDeficiency->handle(
                $application,
                $request->user(),
                (string) $request->string('description'),
                $request->input('field_reference'),
            );
        } catch (RuntimeException $e) {
            return back()->withErrors(['deficiency' => $e->getMessage()]);
        }

        return back()->with('status', __('scrutiny.deficiency_raised'));
    }
}
