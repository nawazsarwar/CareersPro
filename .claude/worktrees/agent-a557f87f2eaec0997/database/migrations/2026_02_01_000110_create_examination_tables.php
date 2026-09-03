<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A sequence per post, taken FOR UPDATE. Roll numbers are gapless,
        // unique and never user-entered: a duplicate roll number on an
        // attendance sheet is two candidates the invigilator cannot tell apart.
        Schema::create('roll_number_sequences', function (Blueprint $table): void {
            $table->foreignId('post_id')->primary()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('next_value')->default(1);
        });

        Schema::create('seat_allocations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_centre_id')->constrained()->restrictOnDelete();
            $table->string('room_no', 32);
            $table->unsignedInteger('seat_no');

            // Which rule placed this candidate here: preference honoured, or
            // fallback by proximity. Recorded so a complaint about allocation
            // can be answered with what happened rather than a guess.
            $table->string('allocation_rule', 32);

            $table->timestamps();

            // Clash-free by construction. A double allocation is impossible,
            // not merely unlikely.
            $table->unique(['exam_centre_id', 'room_no', 'seat_no'], 'seat_unique');
            $table->index(['post_id', 'exam_centre_id']);
        });

        Schema::create('centre_preferences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_centre_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('preference_order');
            $table->timestamps();

            $table->unique(['application_id', 'preference_order'], 'preference_unique');
        });

        Schema::create('generated_documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type', 32);           // admit_card, interview_letter, attendance_sheet
            $table->string('disk', 32)->default('local');
            $table->string('path');
            $table->char('content_hash', 64)->nullable();

            // Printed on the document so a paper copy can be checked against
            // the record it was generated from.
            $table->string('verification_code', 32)->nullable()->unique();

            $table->foreignId('generated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at');
            $table->timestamps();

            $table->index(['application_id', 'type']);
            $table->index(['post_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
        Schema::dropIfExists('centre_preferences');
        Schema::dropIfExists('seat_allocations');
        Schema::dropIfExists('roll_number_sequences');
    }
};
