<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Audit\VerifyAuditChain;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', AuditLog::class);

        $entries = AuditLog::query()
            ->when($request->filled('event'), fn ($q) => $q->where('event', $request->string('event')))
            ->when($request->filled('actor'), fn ($q) => $q->where('actor_id', $request->integer('actor')))
            ->orderByDesc('sequence')
            ->paginate(100)
            ->withQueryString();

        return view('admin.audit.index', ['entries' => $entries]);
    }

    public function show(AuditLog $log): View
    {
        $this->authorize('view', $log);

        return view('admin.audit.show', [
            'entry' => $log,
            'previous' => AuditLog::query()->where('sequence', $log->sequence - 1)->first(),
            'next' => AuditLog::query()->where('sequence', $log->sequence + 1)->first(),
        ]);
    }

    /**
     * M26-R12 — a subject's timeline, in sequence order.
     */
    public function subject(string $type, int $id): View
    {
        $this->authorize('viewAny', AuditLog::class);

        return view('admin.audit.index', [
            'entries' => AuditLog::query()
                ->where('subject_type', $type)
                ->where('subject_id', $id)
                ->orderBy('sequence')
                ->paginate(100),
        ]);
    }

    /**
     * Verification is a visible action with a clear result. A broken chain is
     * a P1 security incident, and the screen says so rather than showing a
     * yellow warning (M26 §7).
     */
    public function verify(VerifyAuditChain $verify): View
    {
        $this->authorize('verify', AuditLog::class);

        return view('admin.audit.verify', ['report' => $verify->handle()]);
    }
}
