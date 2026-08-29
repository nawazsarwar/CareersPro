<?php

declare(strict_types=1);

namespace App\Domain\Examination;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Models\Application;
use App\Models\GeneratedDocument;
use App\Models\User;
use App\Support\Canonical\CanonicalJson;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * The admit card, gated by its download window.
 *
 * The content hash and verification code let a paper copy be checked against
 * the record it came from: an admit card is the document somebody presents at
 * a gate, and "is this the real one" has to be answerable without trusting the
 * paper.
 */
final class GenerateAdmitCard
{
    public function __construct(
        private readonly AssertDownloadWindow $window,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(Application $application, ?User $actor = null): GeneratedDocument
    {
        $this->window->check($application->post, AssertDownloadWindow::ADMIT_CARD);

        if ($application->roll_no === null) {
            throw new RuntimeException('That candidate has no roll number, so no admit card can be issued.');
        }

        $allocation = $application->seatAllocation;

        if ($allocation === null) {
            throw new RuntimeException('That candidate has no seat, so no admit card can be issued.');
        }

        $payload = [
            'application_no' => $application->application_no,
            'roll_no' => $application->roll_no,
            'candidate' => $application->user->name,
            'post' => $application->post->title,
            'centre_id' => (int) $allocation->exam_centre_id,
            'room_no' => $allocation->room_no,
            'seat_no' => (int) $allocation->seat_no,
            'test_date' => $application->post->test_date?->toIso8601String(),
        ];

        $document = GeneratedDocument::query()->updateOrCreate(
            ['application_id' => $application->getKey(), 'type' => 'admit_card'],
            [
                'post_id' => $application->post_id,
                'path' => sprintf('admit-cards/%s.pdf', $application->application_no),
                'content_hash' => CanonicalJson::hash($payload),
                'verification_code' => strtoupper(Str::random(10)),
                'generated_by_id' => $actor?->getKey(),
                'generated_at' => CarbonImmutable::now(),
            ],
        );

        // M26-R07: a document reaching a candidate is a disclosure, and the
        // audit chain exists for exactly that.
        $this->audit->handle(new AuditEvent(
            event: AuditEventName::DocumentAccessed,
            properties: ['type' => 'admit_card', 'application_no' => $application->application_no],
            subject: $application,
            actorId: $actor?->getKey() === null ? null : (int) $actor->getKey(),
        ));

        $application->forceFill(['admit_card_downloaded_at' => CarbonImmutable::now()])->save();

        return $document;
    }
}
