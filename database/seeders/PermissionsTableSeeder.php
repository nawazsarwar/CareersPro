<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'faq_management_access',
            ],
            [
                'id'    => 20,
                'title' => 'faq_category_create',
            ],
            [
                'id'    => 21,
                'title' => 'faq_category_edit',
            ],
            [
                'id'    => 22,
                'title' => 'faq_category_show',
            ],
            [
                'id'    => 23,
                'title' => 'faq_category_delete',
            ],
            [
                'id'    => 24,
                'title' => 'faq_category_access',
            ],
            [
                'id'    => 25,
                'title' => 'faq_question_create',
            ],
            [
                'id'    => 26,
                'title' => 'faq_question_edit',
            ],
            [
                'id'    => 27,
                'title' => 'faq_question_show',
            ],
            [
                'id'    => 28,
                'title' => 'faq_question_delete',
            ],
            [
                'id'    => 29,
                'title' => 'faq_question_access',
            ],
            [
                'id'    => 30,
                'title' => 'career_access',
            ],
            [
                'id'    => 31,
                'title' => 'advertisement_create',
            ],
            [
                'id'    => 32,
                'title' => 'advertisement_edit',
            ],
            [
                'id'    => 33,
                'title' => 'advertisement_show',
            ],
            [
                'id'    => 34,
                'title' => 'advertisement_delete',
            ],
            [
                'id'    => 35,
                'title' => 'advertisement_access',
            ],
            [
                'id'    => 36,
                'title' => 'post_type_create',
            ],
            [
                'id'    => 37,
                'title' => 'post_type_edit',
            ],
            [
                'id'    => 38,
                'title' => 'post_type_show',
            ],
            [
                'id'    => 39,
                'title' => 'post_type_delete',
            ],
            [
                'id'    => 40,
                'title' => 'post_type_access',
            ],
            [
                'id'    => 41,
                'title' => 'post_create',
            ],
            [
                'id'    => 42,
                'title' => 'post_edit',
            ],
            [
                'id'    => 43,
                'title' => 'post_show',
            ],
            [
                'id'    => 44,
                'title' => 'post_delete',
            ],
            [
                'id'    => 45,
                'title' => 'post_access',
            ],
            [
                'id'    => 46,
                'title' => 'setting_access',
            ],
            [
                'id'    => 47,
                'title' => 'marital_status_create',
            ],
            [
                'id'    => 48,
                'title' => 'marital_status_edit',
            ],
            [
                'id'    => 49,
                'title' => 'marital_status_show',
            ],
            [
                'id'    => 50,
                'title' => 'marital_status_delete',
            ],
            [
                'id'    => 51,
                'title' => 'marital_status_access',
            ],
            [
                'id'    => 52,
                'title' => 'disability_type_create',
            ],
            [
                'id'    => 53,
                'title' => 'disability_type_edit',
            ],
            [
                'id'    => 54,
                'title' => 'disability_type_show',
            ],
            [
                'id'    => 55,
                'title' => 'disability_type_delete',
            ],
            [
                'id'    => 56,
                'title' => 'disability_type_access',
            ],
            [
                'id'    => 57,
                'title' => 'religion_create',
            ],
            [
                'id'    => 58,
                'title' => 'religion_edit',
            ],
            [
                'id'    => 59,
                'title' => 'religion_show',
            ],
            [
                'id'    => 60,
                'title' => 'religion_delete',
            ],
            [
                'id'    => 61,
                'title' => 'religion_access',
            ],
            [
                'id'    => 62,
                'title' => 'category_create',
            ],
            [
                'id'    => 63,
                'title' => 'category_edit',
            ],
            [
                'id'    => 64,
                'title' => 'category_show',
            ],
            [
                'id'    => 65,
                'title' => 'category_delete',
            ],
            [
                'id'    => 66,
                'title' => 'category_access',
            ],
            [
                'id'    => 67,
                'title' => 'caste_create',
            ],
            [
                'id'    => 68,
                'title' => 'caste_edit',
            ],
            [
                'id'    => 69,
                'title' => 'caste_show',
            ],
            [
                'id'    => 70,
                'title' => 'caste_delete',
            ],
            [
                'id'    => 71,
                'title' => 'caste_access',
            ],
            [
                'id'    => 72,
                'title' => 'country_create',
            ],
            [
                'id'    => 73,
                'title' => 'country_edit',
            ],
            [
                'id'    => 74,
                'title' => 'country_show',
            ],
            [
                'id'    => 75,
                'title' => 'country_delete',
            ],
            [
                'id'    => 76,
                'title' => 'country_access',
            ],
            [
                'id'    => 77,
                'title' => 'province_create',
            ],
            [
                'id'    => 78,
                'title' => 'province_edit',
            ],
            [
                'id'    => 79,
                'title' => 'province_show',
            ],
            [
                'id'    => 80,
                'title' => 'province_delete',
            ],
            [
                'id'    => 81,
                'title' => 'province_access',
            ],
            [
                'id'    => 82,
                'title' => 'postal_code_create',
            ],
            [
                'id'    => 83,
                'title' => 'postal_code_edit',
            ],
            [
                'id'    => 84,
                'title' => 'postal_code_show',
            ],
            [
                'id'    => 85,
                'title' => 'postal_code_delete',
            ],
            [
                'id'    => 86,
                'title' => 'postal_code_access',
            ],
            [
                'id'    => 87,
                'title' => 'profile_create',
            ],
            [
                'id'    => 88,
                'title' => 'profile_edit',
            ],
            [
                'id'    => 89,
                'title' => 'profile_show',
            ],
            [
                'id'    => 90,
                'title' => 'profile_delete',
            ],
            [
                'id'    => 91,
                'title' => 'profile_access',
            ],
            [
                'id'    => 92,
                'title' => 'photo_create',
            ],
            [
                'id'    => 93,
                'title' => 'photo_edit',
            ],
            [
                'id'    => 94,
                'title' => 'photo_show',
            ],
            [
                'id'    => 95,
                'title' => 'photo_delete',
            ],
            [
                'id'    => 96,
                'title' => 'photo_access',
            ],
            [
                'id'    => 97,
                'title' => 'adress_create',
            ],
            [
                'id'    => 98,
                'title' => 'adress_edit',
            ],
            [
                'id'    => 99,
                'title' => 'adress_show',
            ],
            [
                'id'    => 100,
                'title' => 'adress_delete',
            ],
            [
                'id'    => 101,
                'title' => 'adress_access',
            ],
            [
                'id'    => 102,
                'title' => 'qualification_level_create',
            ],
            [
                'id'    => 103,
                'title' => 'qualification_level_edit',
            ],
            [
                'id'    => 104,
                'title' => 'qualification_level_show',
            ],
            [
                'id'    => 105,
                'title' => 'qualification_level_delete',
            ],
            [
                'id'    => 106,
                'title' => 'qualification_level_access',
            ],
            [
                'id'    => 107,
                'title' => 'board_create',
            ],
            [
                'id'    => 108,
                'title' => 'board_edit',
            ],
            [
                'id'    => 109,
                'title' => 'board_show',
            ],
            [
                'id'    => 110,
                'title' => 'board_delete',
            ],
            [
                'id'    => 111,
                'title' => 'board_access',
            ],
            [
                'id'    => 112,
                'title' => 'academic_qualification_create',
            ],
            [
                'id'    => 113,
                'title' => 'academic_qualification_edit',
            ],
            [
                'id'    => 114,
                'title' => 'academic_qualification_show',
            ],
            [
                'id'    => 115,
                'title' => 'academic_qualification_delete',
            ],
            [
                'id'    => 116,
                'title' => 'academic_qualification_access',
            ],
            [
                'id'    => 117,
                'title' => 'eligibility_test_create',
            ],
            [
                'id'    => 118,
                'title' => 'eligibility_test_edit',
            ],
            [
                'id'    => 119,
                'title' => 'eligibility_test_show',
            ],
            [
                'id'    => 120,
                'title' => 'eligibility_test_delete',
            ],
            [
                'id'    => 121,
                'title' => 'eligibility_test_access',
            ],
            [
                'id'    => 122,
                'title' => 'foreign_visit_create',
            ],
            [
                'id'    => 123,
                'title' => 'foreign_visit_edit',
            ],
            [
                'id'    => 124,
                'title' => 'foreign_visit_show',
            ],
            [
                'id'    => 125,
                'title' => 'foreign_visit_delete',
            ],
            [
                'id'    => 126,
                'title' => 'foreign_visit_access',
            ],
            [
                'id'    => 127,
                'title' => 'referee_create',
            ],
            [
                'id'    => 128,
                'title' => 'referee_edit',
            ],
            [
                'id'    => 129,
                'title' => 'referee_show',
            ],
            [
                'id'    => 130,
                'title' => 'referee_delete',
            ],
            [
                'id'    => 131,
                'title' => 'referee_access',
            ],
            [
                'id'    => 132,
                'title' => 'employment_history_create',
            ],
            [
                'id'    => 133,
                'title' => 'employment_history_edit',
            ],
            [
                'id'    => 134,
                'title' => 'employment_history_show',
            ],
            [
                'id'    => 135,
                'title' => 'employment_history_delete',
            ],
            [
                'id'    => 136,
                'title' => 'employment_history_access',
            ],
            [
                'id'    => 137,
                'title' => 'advertisement_type_create',
            ],
            [
                'id'    => 138,
                'title' => 'advertisement_type_edit',
            ],
            [
                'id'    => 139,
                'title' => 'advertisement_type_show',
            ],
            [
                'id'    => 140,
                'title' => 'advertisement_type_delete',
            ],
            [
                'id'    => 141,
                'title' => 'advertisement_type_access',
            ],
            [
                'id'    => 142,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
