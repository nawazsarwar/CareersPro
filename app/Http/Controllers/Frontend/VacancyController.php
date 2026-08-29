<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Domain\Public\SubmissionInstructions;
use App\Domain\Public\VacancyVisibility;
use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The public vacancy listing (M01) and advertisement detail (M02).
 *
 * Reaches data only through VacancyVisibility, never through a bare query:
 * engineering-standards §2.2 rule 2, and the reason a draft advertisement
 * cannot leak through a guessed slug.
 */
class VacancyController extends Controller
{
    public function __construct(
        private readonly VacancyVisibility $visibility,
        private readonly SubmissionInstructions $instructions,
    ) {}

    public function index(Request $request): View
    {
        $posts = $this->visibility->query()
            ->with(['advertisement', 'postType', 'designation'])
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = str_replace(['%', '_'], ['\%', '\_'], (string) $request->string('q'));
                $query->where(fn (Builder $inner) => $inner
                    ->where('title', 'like', $term.'%')
                    ->orWhere('subject', 'like', $term.'%'));
            })
            ->when($request->filled('nature'), fn (Builder $q) => $q->where('appointment_nature', $request->string('nature')))
            ->when($request->filled('unit'), fn (Builder $q) => $q->where('ou_code_snapshot', $request->string('unit')))
            ->when($request->boolean('open_only'), fn (Builder $q) => $q->whereDate('closing_date', '>=', now()->toDateString()))
            ->orderByDesc('closing_date')
            ->paginate(25)
            ->withQueryString();

        return view('frontend.vacancies.index', ['posts' => $posts]);
    }

    public function advertisement(string $slug): View
    {
        $advertisement = Advertisement::query()
            ->visibleTo(null)
            ->where('slug', $slug)
            ->with(['posts', 'corrigenda', 'type'])
            ->firstOrFail();

        return view('frontend.vacancies.advertisement', ['advertisement' => $advertisement]);
    }

    public function post(string $slug): View
    {
        $post = $this->visibility->query()
            ->where('slug', $slug)
            ->with(['advertisement.corrigenda', 'postType', 'designation'])
            ->firstOrFail();

        return view('frontend.vacancies.post', [
            'post' => $post,
            'instructions' => $this->instructions->for($post),
        ]);
    }
}
