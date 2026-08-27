# Engineering Standards

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-28
**Binding on:** every line of code in this repository. CI enforces what can be enforced; code review
enforces the rest.

**Stack:** PHP 8.5 · Laravel 13.26 · Tailwind CSS 4 · Alpine.js 3 · Pest 5 · Larastan 3 · Pint

---

## 1. The governing rule

**Follow Laravel's own conventions. Where this document is silent, the framework's default wins —
not personal preference, and not a pattern imported from another ecosystem.**

If you find yourself writing something the framework already does, stop and use the framework. The
v2 codebase is what happens when that rule is ignored: 162 `Gate::define()` closures rebuilt on every
request instead of policies, a hand-rolled verification flow alongside Laravel's own, and a
`username()` override that could not express the requirement.

---

## 2. Directory and namespace layout

### 2.1 The Admin / Frontend split — mandatory

**Every HTTP-facing artefact lives under an `Admin` or `Frontend` namespace. No exceptions, no
top-level controllers.**

```
app/Http/Controllers/Admin/AdvertisementController.php
app/Http/Controllers/Frontend/VacancyController.php
app/Http/Requests/Admin/StoreAdvertisementRequest.php
app/Http/Requests/Frontend/SubmitApplicationRequest.php
resources/views/admin/advertisements/index.blade.php
resources/views/frontend/vacancies/index.blade.php
routes/admin.php
routes/frontend.php
```

Route files are registered in `bootstrap/app.php` with their own prefix, name prefix and middleware
group:

```php
->withRouting(
    web: base_path('routes/web.php'),
    then: function () {
        Route::middleware(['web', 'auth', 'verified', 'two-factor'])
            ->prefix('admin')->name('admin.')
            ->group(base_path('routes/admin.php'));

        Route::middleware(['web'])
            ->name('frontend.')
            ->group(base_path('routes/frontend.php'));
    },
)
```

### 2.2 The rule that stops v2 happening again

The v2 defect was **not** the Admin/Frontend split — the split is correct and is what we are doing.
The defect was that `Frontend/*` were **byte-for-byte copies** of `Admin/*` with the route names
swapped and **no ownership scoping**, so any candidate could edit any other candidate's dossier.

**Therefore:**

1. **An Admin and a Frontend controller must never share an implementation by copying.** Shared
   behaviour goes into a domain action or a query object that both call.
2. **Every Frontend controller reaches data only through a policy-scoped query.** A bare
   `Model::find()` or `Model::all()` in a Frontend controller fails review and fails
   `tests/Architecture/FrontendScopingTest`.
3. **Frontend controllers expose a candidate's own records only.** Staff read a candidate's data
   through the Admin side, scoped by `ScopedPolicy`.

### 2.3 Full layout

```
app/
  Console/Commands/
  Domain/{Context}/            ← see §3. NOT the default; justified per context
  Enums/                       ← backed enums, one per file
  Http/
    Controllers/Admin/
    Controllers/Frontend/
    Controllers/Api/V1/
    Middleware/
    Requests/Admin/
    Requests/Frontend/
    Resources/Api/V1/
  Jobs/
  Mail/
  Models/                      ← Eloquent only. No business logic beyond relations/scopes/casts
  Notifications/
  Observers/
  Policies/
  Providers/
  Rules/                       ← custom validation rules
  Support/                     ← framework-adjacent helpers (Table, CanonicalJson)
```

---

## 3. The Domain layer — what it is, and when it is *not* used

You asked for this to be justified. Here is the honest case, including the argument against.

### 3.1 First, the concession

**`App\Domain\` is not a Laravel framework convention.** The skeleton ships `app/Models`,
`app/Http` and `app/Providers`. The closest first-party precedent is Fortify and Jetstream, which
use `App\Actions\{Package}\CreateNewUser`. So if "follow Laravel conventions" were applied without
qualification, the answer would be `App\Actions\{Context}\`.

**We are still using `App\Domain\{Context}\`, for four reasons specific to this system.** If you
disagree after reading them, renaming to `App\Actions\` is a one-line Rector rule and nothing else in
the design changes.

### 3.2 Reason 1 — statutory logic must be testable without HTTP

M20's acceptance test runs a **golden corpus of ~30 real candidate profiles** through the scoring
engine and compares each total against a hand-computed figure citing the clause it exercises. It is
the test that would have caught the fabricated `ugc-rules.yaml`.

If that logic lives in a controller, the test needs a route, a session, an authenticated user and an
HTTP round trip — and it then proves the arithmetic *plus* the routing *plus* the authorisation. When
it fails you do not know which broke.

```php
// Domain: a pure unit test. No HTTP, no database round trip beyond the fixture.
$run = (new WeightedPointsStrategy)->score($snapshot, $rulesetV1);
expect($run->total)->toBe(92.5);
```

### 3.3 Reason 2 — the same logic has three entry points

Scoring is invoked from **a controller** (an officer triggers a run), **a queued job** (bulk rescore
after the Executive Council ratifies T2-AMB-01), and **a console command** (the sandbox in M20 §7).
Reservation relaxation is invoked from the eligibility pre-check, the scrutiny workbench and the fee
calculator.

Logic in a controller means the job and the command either duplicate it or call the controller. Both
are how v2 ended up with 35 Frontend controllers that were copies of 37 Admin controllers.

### 3.4 Reason 3 — polymorphism the framework cannot express

`ScoringStrategy` has **four** implementations chosen by the *frozen* ruleset, because UGC 2025
abolishes the Research Score and computes nothing. `MeritStrategy` has **two**, and one of them
**throws** on an input the other accepts:

```php
final class TeachingMeritStrategy implements MeritStrategy
{
    public function rank(array $inputs): MeritList
    {
        if (isset($inputs['shortlisting_score'])) {
            throw new StatutoryViolation(
                'UGC 2018 cl. 4.1 I Note: shortlisting score must not enter a teaching merit list'
            );
        }
        // …
    }
}
```

That is a strategy pattern carrying a statutory contract. It cannot be a model method or a controller
action.

### 3.5 Reason 4 — invariants need one home

*"A rejection requires a remark."* *"PI and Co-PI get 50% each."* *"Age is computed against
`posts.reg_end_date`, never today."* These hold **wherever** the operation is performed.

- In a **Form Request** they hold only for HTTP input — not for the console command, not for the job.
- In a **model** every model becomes a god object.
- In a **domain action** they hold at every call site, and one test proves it.

### 3.6 The argument against, and the rule that answers it

**Most of this application is CRUD.** M24 alone is 20-odd lookup tables. A `CreateCasteAction` is
ceremony with no payoff, and over-applying the pattern is a real failure mode.

> **The domain layer is not the default.** A context earns one only if it meets **at least one** of:
>
> 1. the logic is **statutory** — a regulation dictates it;
> 2. it has **more than one entry point** — HTTP, job, or console;
> 3. it is **polymorphic** — multiple implementations behind an interface.
>
> Everything else is plain Laravel: **route → controller → Form Request → model → Blade.**

**Contexts that qualify** (from the 36 module specs): `Scoring` · `Merit` · `Relaxation` ·
`Eligibility` · `Application` · `Recruitment` · `Establishment` · `Payment` · `Audit` · `Legal` ·
`Migration` · `Organisation` · `Identity` · `Access` · `Documents` · `Scrutiny` · `Committee` ·
`Examination` · `Interview` · `Shortlist` · `Communication` · `Grievance` · `Reporting` · `Custody` ·
`Claims` · `Dossier` · `Api` · `System` · `Public`.

**Contexts that do not:** every master-data CRUD in M24.

### 3.7 Shape of a domain class

**One public method.** Named `handle()` for a command, `for()` for a query, `check()` for a guard.

```php
namespace App\Domain\Eligibility;

final readonly class DecideGate
{
    public function __construct(private AuditRecorder $audit) {}

    public function handle(
        Application $application,
        Gate $gate,
        ?Decision $decision,
        ?string $remark,
        User $actor,
    ): EligibilityDecision {
        // …
    }
}
```

`final` by default · `readonly` where it holds no mutable state · constructor property promotion ·
dependencies injected, never resolved from the container inside the method · **no facades in the
domain layer** (they hide dependencies and make the unit test lie).

---

## 4. Controllers

**Thin. No business logic, no validation, no queries beyond a scoped retrieval.**

```php
final class AdvertisementController extends Controller
{
    public function store(StoreAdvertisementRequest $request, CreateAdvertisement $action): RedirectResponse
    {
        $advertisement = $action->handle($request->toDto(), $request->user());

        return to_route('admin.advertisements.show', $advertisement)
            ->with('status', __('advertisements.created'));
    }
}
```

- **Resourceful** where the resource is a resource; **invokable** (`__invoke`) for a genuine
  single action. No `SomeController@doTheThing`.
- Return `RedirectResponse`, `View` or `JsonResponse` — always type-hinted.
- `to_route()` over `redirect()->route()`.
- **A controller method over ~15 lines is a smell.** Push it down.

---

## 5. Validation — Form Requests, strictly

**Every request that carries input is validated by a Form Request class. No exceptions.**

**Banned outright, and enforced by `tests/Architecture/ValidationTest`:**

- `$request->validate([...])` in a controller
- `Validator::make(...)` in a controller
- validation inside a model, action or job as a substitute for a Form Request

```php
namespace App\Http\Requests\Admin;

final class StoreAdvertisementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Advertisement::class);
    }

    /** @return array<string, list<mixed>> */
    public function rules(): array
    {
        return [
            'advertisement_no'   => ['required', 'string', 'max:100', Rule::unique('advertisements')],
            'appointment_nature' => ['required', Rule::enum(AppointmentNature::class)],
            'tenure_months'      => ['nullable', 'integer', 'between:1,12',
                                     Rule::requiredIf($this->appointmentNature() === AppointmentNature::Local)],
            'default_closing_date' => ['required', 'date', 'after_or_equal:default_opening_date',
                                       new AtLeastThirtyDaysAfterPublish],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'tenure_months.required' => __('validation.custom.tenure_required_for_local'),
        ];
    }
}
```

**Rules:**

- Rules as **arrays**, never pipe strings — `['required', 'string']`, not `'required|string'`.
- **`Rule::` builders** over string rules where one exists: `Rule::enum()`, `Rule::unique()`,
  `Rule::exists()`, `Rule::in()`.
- **Cross-field rules belong in the Form Request**, either in `rules()` or `after()` — not in the
  controller and not in the action.
- **Complex, reusable rules become `App\Rules\` classes** with their own unit test. The 36 specs
  already name several: `WithinSanctionedStrength`, `SelectableOrganisationalUnit`,
  `MimeMatchesContent`, `NoEmploymentOverlap`.
- **Every message is specified.** The module specs give the exact wording; use it verbatim.
- Messages live in `lang/en/validation.php` under `custom`, never inline English in the class.
- `authorize()` delegates to a **policy**. It never re-implements one.
- Expose a `toDto()` returning a readonly DTO where the action takes more than three arguments.

---

## 6. Models

```php
final class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['advertisement_no', 'title', /* … */];

    protected function casts(): array
    {
        return [
            'appointment_nature' => AppointmentNature::class,
            'published_at'       => 'immutable_datetime',
            'segment'            => 'array',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at');
    }
}
```

- **`$fillable`, never `$guarded = []`.** Mass-assignment protection is not optional on a system
  holding statutory decisions.
- **`casts()` method**, not the `$casts` property (Laravel 11+).
- **Backed enums for every status.** Never a loose int or string — v2 wrote the *string*
  `'Submitted'` into an integer column.
- Relationships and scopes only. **No business logic, no HTTP, no formatting.**
- **`#[Scope]` attribute** for local scopes (Laravel 12+), not the `scopeFoo` prefix.
- Every relationship and scope carries a **return type**.
- **No queries in Blade.** Ever. Eager-load in the controller or the query object.

---

## 7. Migrations, enums, database

- Laravel's naming: `2026_08_28_143000_create_advertisements_table.php`. One concern per migration.
- **Table and column naming per [`../00-clarify/glossary.md`](../00-clarify/glossary.md) §7** —
  snake_case plural, British spelling, no double-pluralisation, no unexplained acronyms.
- **Real foreign keys.** `->constrained()`, with the `onDelete` behaviour stated explicitly.
- **Every `sortable` or `filterable` column has an index**, asserted by
  `tests/Unit/Table/IndexCoverageTest`.
- **`json` columns for genuinely dynamic payloads** (ADR-001 / DR-003) — never `longtext`.
- **`down()` on every migration.** A migration you cannot reverse is a migration you cannot deploy
  with confidence.
- Enums live in `app/Enums`, backed by `string` unless the domain demands otherwise.

---

## 8. Testing — Pest

**Pest 5. Feature tests by default; unit tests for the domain layer.**

```
tests/
  Feature/{Admin,Frontend}/…      mirrors the controller namespaces
  Unit/Domain/{Context}/…         mirrors app/Domain
  Architecture/…                  the rules in §2.2, §5, §10
  Fixtures/golden-corpus/…
```

```php
it('refuses to score when a required rule is unratified', function () {
    $run = fn () => (new WeightedPointsStrategy)->score($snapshot, $ruleset);

    expect($run)->toThrow(PendingRatificationError::class);
});

it('applies 50% to a Co-Investigator', function () {
    expect(Apportion::for($coPiClaim, $ruleset))->toBe(0.50);   // REG-01
});
```

- **`it()` for behaviour**, `test()` only where `it` reads badly. Descriptions are sentences.
- **One assertion concept per test.** Datasets (`->with([...])`) for table-driven cases such as the
  age-relaxation grid.
- **Factories for all fixtures.** No hand-built arrays, no seeded production data.
- `RefreshDatabase` on feature tests; the domain layer needs no database where the input is a
  snapshot.
- **Every acceptance criterion `M{NN}-R{NN}` maps to a named test.** CI fails on an unmapped ID.
- **Coverage gate: 100% on `app/Domain/Scoring`**, ratcheting elsewhere. A coverage driver must be
  installed — today there is none, and `docs/v2-archive/` records a suite of 4 tests and 6
  assertions.

---

## 9. Frontend

- **Tailwind CSS 4** with `@theme` in `resources/css/app.css`. **No `tailwind.config.js`** —
  matching the current Vite setup.
- **Bootstrap, jQuery, DataTables, Select2, Dropzone, CKEditor and perfect-scrollbar are removed and
  must not return.** CI greps the built output.
- **Tokens only.** No hex value appears in a Blade template
  ([`ux/design-system.md`](ux/design-system.md) §2).
- **Blade components over includes.** `<x-ui.button>`, not `@include('partials.button')`.
- **No logic in views.** No queries, no business conditionals — presentation only.
- **Alpine for interaction**, per §10.
- Every rendered route is **`axe-core` clean**; WCAG 2.2 AA and GIGW are acceptance criteria, not
  aspirations.

---

## 10. UI framework — decision

**Blade + Alpine.js + Tailwind CSS 4. Everywhere. No exceptions.**

| Surface | Stack |
|---|---|
| Candidate-facing (M01–M15) | Blade + Alpine + Tailwind 4 |
| Admin (M16–M35) | Blade + Alpine + Tailwind 4 |

**No Livewire. No Inertia. No Vue, React, jQuery or SPA of any kind.** One paradigm, one language,
one mental model, for the whole application and its whole life.

**Everything works with JavaScript disabled.** Alpine is progressive enhancement, never a
dependency. Every form is a real `<form method="POST">`, every filter a real `GET`, every link a
real link. Alpine makes those interactions nicer; it is never the only way to perform them. See
§10.2.

### 10.1 How dense interactive screens work without Livewire

Three screens are genuinely interaction-dense — M18 scrutiny workbench, M08 reconciliation queue,
M20 ruleset sandbox. They use **one pattern**, and it is the only pattern permitted:

> **A small JSON endpoint + `fetch` from Alpine, with a non-JS form fallback on the same route.**

```blade
{{-- Non-JS path: a real form. Works with Alpine absent. --}}
<form method="POST" action="{{ route('admin.scrutiny.claims.verify', [$application, $claim]) }}"
      x-data="claimRow({{ $claim->id }})"
      x-on:submit.prevent="verify()">
    @csrf
    <button type="submit" x-text="busy ? 'Saving…' : 'Verify'">Verify</button>
</form>
```

```js
// resources/js/scrutiny.js
Alpine.data('claimRow', (id) => ({
    busy: false,
    async verify() {
        this.busy = true;
        const res = await fetch(`/admin/scrutiny/claims/${id}/verify`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
        });
        this.busy = false;
        this.$dispatch('claim-verified', await res.json());   // row updates in place
    },
}));
```

The controller returns **JSON when the request wants JSON, a redirect otherwise** — one action, two
representations:

```php
return $request->expectsJson()
    ? response()->json(ClaimResource::make($claim))
    : back()->with('status', __('scrutiny.claim_verified'));
```

**Long-running work is a queued job plus a status endpoint that Alpine polls.** Reconciliation, bulk
document generation and sandbox runs are already queued for their own reasons; polling
`GET /admin/…/{id}/status` every two seconds is all the "live" any of them needs, and the page still
works if you simply reload it.

### 10.2 What this buys, and what it costs

**Buys:** one paradigm instead of two · no component state on the server · every interactive endpoint
is an ordinary route with an ordinary test · **the no-JS path survives on the admin side too**, which
under a Livewire plan those three screens would have lost · no additional dependency on a
five-year-lived statutory system.

**Costs, stated plainly:** an interaction takes an endpoint *and* a handler, where Livewire would
take one component method. That is perhaps 15–20 extra lines on each of three screens. **We are
paying it deliberately**, and it buys explicitness and testability.

### 10.3 Tailwind

Tailwind CSS 4, `@theme` in `resources/css/app.css`, **no `tailwind.config.js`**, tokens per
[`ux/design-system.md`](ux/design-system.md) §2. **No hex value in a Blade template.** Bootstrap,
jQuery, DataTables, Select2, Dropzone, CKEditor and perfect-scrollbar are removed and CI greps the
built output to keep them out.

## 11. Tooling and CI

| Tool | Configuration | Gate |
|---|---|---|
| **Pint** | `laravel` preset, `pint.json` committed | `pint --test` blocks |
| **Larastan** | **level 6**, ratcheting. Baseline entries require a comment giving the reason and the ticket | blocks |
| **Pest** | `--coverage --min` per the gate in §8 | blocks |
| `composer audit` | | blocks on high severity |
| `npm audit` | | blocks on high severity |
| **gitleaks** | | blocks |
| **axe-core** | every rendered route | blocks |

**`declare(strict_types=1);` at the top of every PHP file**, enforced by Pint.

### 11.1 Architecture tests

Pest's architecture plugin encodes what review would otherwise have to catch:

```php
arch('controllers do not validate inline')
    ->expect('App\Http\Controllers')
    ->not->toUse(['Illuminate\Support\Facades\Validator']);

arch('the domain layer uses no facades')
    ->expect('App\Domain')->not->toUse('Illuminate\Support\Facades');

arch('models hold no HTTP')
    ->expect('App\Models')->not->toUse('Illuminate\Http');

arch('domain classes are final')
    ->expect('App\Domain')->toBeFinal();

arch('enums are backed')
    ->expect('App\Enums')->toBeStringBackedEnums();

arch('no gateway vendor leaks out of its adapter')   // DR-018
    ->expect(['Razorpay', 'Billdesk'])
    ->toOnlyBeUsedIn('App\Domain\Payment\Gateways');
```

Plus the project-specific ones already named in the specs: `NoRosterTest` (DR-017),
`AutonomyTest` (DR-009), `NoDomainDeletionTest` (DR-011), `FrontendScopingTest` (§2.2),
`GatewayAgnosticTest` (DR-018), `ApiReadOnlyTest` (M29).

---

## 12. UI framework comparison

Assessed against **this** project: 78,232-row admin tables, an 11-section statutory form, WCAG 2.2 AA
and GIGW, a PHP-first team, and a system with a 5+ year statutory life.

| | **Blade + Alpine** | Livewire 4 | Inertia + Vue/React | Filament 5 | Flux UI |
|---|---|---|---|---|---|
| Server-rendered HTML | ✅ | ✅ | ❌ | ✅ | ✅ |
| **Works with JS disabled** | ✅ | ❌ | ❌ | ❌ | ❌ |
| Second language required | ❌ | ❌ | ✅ JS/TS | ❌ | ❌ |
| Accessibility effort | **lowest** | low | **highest** | low | low |
| 78K-row server-side tables | ✅ built | ✅ | ✅ | ✅ built-in | ✅ |
| Dense interactive screens | ✅ via §10.1 | ✅ terser | ✅ | ✅ | ✅ |
| Matches the reference screenshots | ✅ full control | ✅ full control | ✅ | ⚠️ **fights it** | ⚠️ own design language |
| Custom design system | ✅ | ✅ | ✅ | ⚠️ theming only | ❌ opinionated |
| Paradigms in the codebase | **1** | 2 | 2 | 2 | 2 |
| Server-side component state | **none** | per component | none | per component | per component |
| Team skill surface | **smallest** | small | **largest** | small | small |
| Upgrade risk over 5 years | **lowest** | low | medium | **highest** | medium |
| Licence | free | free | free | free | **$99/dev** |
| **Verdict** | **✅ adopted** | rejected | rejected | rejected | rejected |

### 12.1 Why not Inertia

**It fails a hard constraint.** The candidate side must work without JavaScript — a GIGW expectation,
recorded in [`ux/data-table.md`](ux/data-table.md) §9, and a practical one for candidates applying on
poor connections. Inertia cannot render without JS. It also introduces a second language and skill
set for a PHP-first team, on a system that must be maintainable by whoever inherits it in 2031.

### 12.2 Why not Filament

The closest call, and it deserves an honest number.

**What it would buy:** M24 is 20-odd master-data CRUDs, built in a fraction of the time with tables,
filters and exports for free.

**What it would cost:** of 36 modules roughly **3** are plain CRUD. The other 33 need custom UI
matching the production reference screens — the composite `106 / 63 / 58 / 13⚑` cell, the three-panel
pipeline widget, the 7-column dossier record, the three-gate control, the attendance and
bulk-document generators. **Filament earns its keep on about 8% of the surface while imposing its
design language on 100% of it**, against the deliberate AMU identity in
[`ux/design-system.md`](ux/design-system.md) built on `#0c4a2e` and Victoria Gate.

It is also a large dependency with its own major-version cadence, on a five-year statutory system
carrying an audit obligation.

**If master-data CRUD becomes a bottleneck**, the cheaper answer is a generator command emitting
controller + Form Request + Blade against the shared table component.

### 12.3 Why not Livewire

Livewire was initially proposed for three admin screens. **It is not adopted anywhere**, by sponsor
decision, and on reflection the reasoning is sound beyond preference:

- **A second paradigm is a permanent tax.** Two ways to build a screen means two ways to debug one,
  two sets of conventions to teach, and a standing question of "which should this be?" at the top of
  every ticket. One paradigm removes the question.
- **It would have cost the no-JS path on exactly the screens that most need resilience.** Scrutiny
  and reconciliation are where an officer must be able to act on a degraded connection.
- **Server-side component state is state to reason about**, on a system where every statutory action
  must be auditable and reproducible. Plain endpoints keep the audit boundary where the domain
  action already is.
- The interaction density is real but modest, and §10.1 handles it in ~20 extra lines per screen.

**Flux UI** follows Livewire out, and would in any case have conflicted with a specified design
system.

## 13. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-28 | Created. Laravel conventions as the governing rule; mandatory Admin/Frontend namespace split with the anti-duplication rule; Form Requests strictly, with inline validation banned by architecture test; the Domain layer justified and bounded by a three-part earning test; Pest 5; Larastan level 6; Tailwind 4 with Bootstrap and jQuery removed; UI framework compared and decided. | Implementation team |
