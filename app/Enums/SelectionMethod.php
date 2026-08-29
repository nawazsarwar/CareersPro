<?php

declare(strict_types=1);

namespace App\Enums;

enum SelectionMethod: string
{
    case InterviewOnly = 'interview_only';
    case WrittenInterview = 'written_interview';
    case WrittenSkillInterview = 'written_skill_interview';
    case TradeTest = 'trade_test';
    case DrivingTest = 'driving_test';

    /**
     * Which of the three eligibility gates this method actually uses.
     *
     * The legacy modal enabled all three on every post type, including
     * interview-only ones, so an officer could record a written-test decision
     * for a post that has no written test.
     *
     * @return list<string>
     */
    public function activeGates(): array
    {
        return match ($this) {
            self::InterviewOnly => ['scrutiny', 'interview'],
            self::WrittenInterview,
            self::WrittenSkillInterview => ['scrutiny', 'written_test', 'interview'],
            self::TradeTest, self::DrivingTest => ['scrutiny', 'written_test'],
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::InterviewOnly => __('establishment.method_interview_only'),
            self::WrittenInterview => __('establishment.method_written_interview'),
            self::WrittenSkillInterview => __('establishment.method_written_skill_interview'),
            self::TradeTest => __('establishment.method_trade_test'),
            self::DrivingTest => __('establishment.method_driving_test'),
        };
    }
}
