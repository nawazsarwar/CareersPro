<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\SelectionMethod;
use App\Models\AdvertisementType;
use App\Models\Category;
use App\Models\Country;
use App\Models\DisabilityType;
use App\Models\HorizontalCategory;
use App\Models\MaritalStatus;
use App\Models\PayLevel;
use App\Models\PostType;
use App\Models\Province;
use App\Models\QualificationLevel;
use App\Models\Religion;
use Illuminate\Database\Seeder;

/**
 * The reference masters, actually populated.
 *
 * Every lookup table in the previous build was empty after seeding, so every
 * dropdown in the system rendered blank. These are the rows the statutory
 * documents name, not placeholders.
 */
class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->categories();
        $this->disabilityTypes();
        $this->qualificationLevels();
        $this->payLevels();
        $this->postTypes();
        $this->geography();
        $this->miscellaneous();
    }

    private function categories(): void
    {
        // UGC 2018 cl. 3.4 I names SC/ST/OBC-NCL and PwBD; EWS comes from a
        // separate OM and is absent from the Regulations entirely, which is
        // exactly why it has to be listed explicitly rather than inferred.
        $categories = [
            ['UR', 'Unreserved', false, false],
            ['SC', 'Scheduled Caste', true, false],
            ['ST', 'Scheduled Tribe', true, false],
            ['OBC-NCL', 'Other Backward Class (Non-Creamy Layer)', true, true],
            ['EWS', 'Economically Weaker Section', true, true],
        ];

        foreach ($categories as [$code, $name, $requiresCertificate, $expires]) {
            Category::query()->updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'requires_certificate' => $requiresCertificate, 'certificate_expires' => $expires],
            );
        }

        // Horizontal, and therefore a separate table: a candidate is SC AND a
        // person with disability, never one instead of the other.
        foreach ([
            ['PWBD', 'Person with Benchmark Disability'],
            ['ESM', 'Ex-Serviceman'],
            ['WOMEN', 'Woman'],
        ] as [$code, $name]) {
            HorizontalCategory::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }

    private function disabilityTypes(): void
    {
        // The five categories of UGC 2018 cl. 3.4 I.
        foreach ([
            ['BLIND_LV', 'Blindness and low vision'],
            ['DEAF_HH', 'Deaf and hard of hearing'],
            ['LOCOMOTOR', 'Locomotor disability, including cerebral palsy, leprosy cured, dwarfism, acid attack victims and muscular dystrophy'],
            ['AUTISM_ID_LD', 'Autism, intellectual disability, specific learning disability and mental illness'],
            ['MULTIPLE', 'Multiple disabilities from among the above'],
        ] as [$code, $name]) {
            DisabilityType::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }

    private function qualificationLevels(): void
    {
        // ncrf_level is nullable under UGC 2018 and required under the 2025
        // draft, so the values are carried now rather than back-filled later
        // across submitted applications.
        foreach ([
            ['X', 'Class X', 1, 3],
            ['XII', 'Class XII', 2, 4],
            ['DIPLOMA', 'Diploma', 3, 5],
            ['UG', 'Bachelor’s degree', 4, 6],
            ['PG', 'Master’s degree', 5, 7],
            ['MPHIL', 'M.Phil.', 6, 8],
            ['PHD', 'Ph.D.', 7, 8],
            ['DSC', 'D.Sc. / D.Litt.', 8, null],
        ] as [$code, $name, $rank, $ncrf]) {
            QualificationLevel::query()->updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'rank' => $rank, 'ncrf_level' => $ncrf],
            );
        }
    }

    private function payLevels(): void
    {
        // 7th CPC. F-3 already carries this block; FN-1 still says "Scale of
        // Pay", and the plan adopts the F-3 shape for both.
        $entryPay = [
            1 => 18000, 2 => 19900, 3 => 21700, 4 => 25500, 5 => 29200,
            6 => 35400, 7 => 44900, 8 => 47600, 9 => 53100, 10 => 56100,
            11 => 67700, 12 => 78800, 13 => 123100, 14 => 144200,
        ];

        foreach ($entryPay as $level => $pay) {
            PayLevel::query()->updateOrCreate(
                ['code' => 'L'.$level],
                ['name' => 'Level '.$level, 'entry_pay' => $pay],
            );
        }

        // The academic levels, which are not the same series.
        foreach ([['A10', 'Academic Level 10', 57700], ['A11', 'Academic Level 11', 68900],
            ['A12', 'Academic Level 12', 79800], ['A13A', 'Academic Level 13A', 131400],
            ['A14', 'Academic Level 14', 144200]] as [$code, $name, $pay]) {
            PayLevel::query()->updateOrCreate(['code' => $code], ['name' => $name, 'entry_pay' => $pay]);
        }
    }

    private function postTypes(): void
    {
        // Seven live rows (DR-007). The apparent duplicates are the General and
        // Local regimes of DR-010: different committees and administration,
        // identical eligibility.
        foreach ([
            ['TEACH-GEN', 'Teaching (General)', SelectionMethod::InterviewOnly, 'Joint Registrar, Selection Committee Section (Teaching)'],
            ['TEACH-LOC', 'Teaching (Local)', SelectionMethod::InterviewOnly, 'Office of the Dean of the Faculty'],
            ['NT-GEN', 'Non-Teaching (General)', SelectionMethod::WrittenSkillInterview, 'Joint Registrar, Selection Committee (Non-Teaching)'],
            ['NT-LOC', 'Non-Teaching (Local)', SelectionMethod::WrittenInterview, 'Office of the Dean of the Faculty'],
            ['LIB', 'Library', SelectionMethod::InterviewOnly, 'Joint Registrar, Selection Committee (Non-Teaching)'],
            ['PE', 'Physical Education', SelectionMethod::InterviewOnly, 'Joint Registrar, Selection Committee (Non-Teaching)'],
            ['SCHOOL', 'School Teaching', SelectionMethod::WrittenInterview, 'Directorate of School Education'],
        ] as [$code, $name, $method, $venue]) {
            $gates = $method->activeGates();

            PostType::query()->updateOrCreate(['code' => $code], [
                'name' => $name,
                'default_selection_method' => $method,
                'submission_venue' => $venue,
                'has_scrutiny_gate' => in_array('scrutiny', $gates, true),
                'has_written_test_gate' => in_array('written_test', $gates, true),
                'has_interview_gate' => in_array('interview', $gates, true),
            ]);
        }

        foreach ([
            ['GENERAL', 'General (permanent)'],
            ['LOCAL', 'Local (temporary, 6–12 months)'],
            ['CORRIGENDUM', 'Corrigendum'],
        ] as [$code, $name]) {
            AdvertisementType::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }

    private function geography(): void
    {
        $india = Country::query()->updateOrCreate(
            ['code' => 'IN'],
            ['name' => 'India', 'iso2' => 'IN', 'iso3' => 'IND'],
        );

        // The states and union territories. Districts and PIN codes are a
        // bulk import (M24), not a hand-written list.
        foreach ([
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa',
            'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala',
            'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland',
            'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
            'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
            'Andaman and Nicobar Islands', 'Chandigarh',
            'Dadra and Nagar Haveli and Daman and Diu', 'Delhi', 'Jammu and Kashmir',
            'Ladakh', 'Lakshadweep', 'Puducherry',
        ] as $state) {
            Province::query()->updateOrCreate(
                ['country_id' => $india->getKey(), 'name' => $state],
                [],
            );
        }
    }

    private function miscellaneous(): void
    {
        foreach ([
            ['ISLAM', 'Islam'], ['HINDU', 'Hinduism'], ['CHRISTIAN', 'Christianity'],
            ['SIKH', 'Sikhism'], ['BUDDHIST', 'Buddhism'], ['JAIN', 'Jainism'],
            ['PARSI', 'Zoroastrianism'], ['OTHER', 'Other'],
            ['NOT_STATED', 'Prefer not to say'],
        ] as [$code, $name]) {
            Religion::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }

        foreach ([
            ['SINGLE', 'Single'], ['MARRIED', 'Married'], ['WIDOWED', 'Widowed'],
            ['DIVORCED', 'Divorced'], ['SEPARATED', 'Separated'],
        ] as [$code, $name]) {
            MaritalStatus::query()->updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }
}
