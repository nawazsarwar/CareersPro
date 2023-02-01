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
                'title' => 'career_access',
            ],
            [
                'id'    => 18,
                'title' => 'advertisement_create',
            ],
            [
                'id'    => 19,
                'title' => 'advertisement_edit',
            ],
            [
                'id'    => 20,
                'title' => 'advertisement_show',
            ],
            [
                'id'    => 21,
                'title' => 'advertisement_delete',
            ],
            [
                'id'    => 22,
                'title' => 'advertisement_access',
            ],
            [
                'id'    => 23,
                'title' => 'advertisement_type_create',
            ],
            [
                'id'    => 24,
                'title' => 'advertisement_type_edit',
            ],
            [
                'id'    => 25,
                'title' => 'advertisement_type_show',
            ],
            [
                'id'    => 26,
                'title' => 'advertisement_type_delete',
            ],
            [
                'id'    => 27,
                'title' => 'advertisement_type_access',
            ],
            [
                'id'    => 28,
                'title' => 'post_type_create',
            ],
            [
                'id'    => 29,
                'title' => 'post_type_edit',
            ],
            [
                'id'    => 30,
                'title' => 'post_type_show',
            ],
            [
                'id'    => 31,
                'title' => 'post_type_delete',
            ],
            [
                'id'    => 32,
                'title' => 'post_type_access',
            ],
            [
                'id'    => 33,
                'title' => 'post_create',
            ],
            [
                'id'    => 34,
                'title' => 'post_edit',
            ],
            [
                'id'    => 35,
                'title' => 'post_show',
            ],
            [
                'id'    => 36,
                'title' => 'post_delete',
            ],
            [
                'id'    => 37,
                'title' => 'post_access',
            ],
            [
                'id'    => 38,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 39,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 40,
                'title' => 'faq_management_access',
            ],
            [
                'id'    => 41,
                'title' => 'faq_category_create',
            ],
            [
                'id'    => 42,
                'title' => 'faq_category_edit',
            ],
            [
                'id'    => 43,
                'title' => 'faq_category_show',
            ],
            [
                'id'    => 44,
                'title' => 'faq_category_delete',
            ],
            [
                'id'    => 45,
                'title' => 'faq_category_access',
            ],
            [
                'id'    => 46,
                'title' => 'faq_question_create',
            ],
            [
                'id'    => 47,
                'title' => 'faq_question_edit',
            ],
            [
                'id'    => 48,
                'title' => 'faq_question_show',
            ],
            [
                'id'    => 49,
                'title' => 'faq_question_delete',
            ],
            [
                'id'    => 50,
                'title' => 'faq_question_access',
            ],
            [
                'id'    => 51,
                'title' => 'profile_create',
            ],
            [
                'id'    => 52,
                'title' => 'profile_edit',
            ],
            [
                'id'    => 53,
                'title' => 'profile_show',
            ],
            [
                'id'    => 54,
                'title' => 'profile_delete',
            ],
            [
                'id'    => 55,
                'title' => 'profile_access',
            ],
            [
                'id'    => 56,
                'title' => 'marital_status_create',
            ],
            [
                'id'    => 57,
                'title' => 'marital_status_edit',
            ],
            [
                'id'    => 58,
                'title' => 'marital_status_show',
            ],
            [
                'id'    => 59,
                'title' => 'marital_status_delete',
            ],
            [
                'id'    => 60,
                'title' => 'marital_status_access',
            ],
            [
                'id'    => 61,
                'title' => 'disability_type_create',
            ],
            [
                'id'    => 62,
                'title' => 'disability_type_edit',
            ],
            [
                'id'    => 63,
                'title' => 'disability_type_show',
            ],
            [
                'id'    => 64,
                'title' => 'disability_type_delete',
            ],
            [
                'id'    => 65,
                'title' => 'disability_type_access',
            ],
            [
                'id'    => 66,
                'title' => 'religion_create',
            ],
            [
                'id'    => 67,
                'title' => 'religion_edit',
            ],
            [
                'id'    => 68,
                'title' => 'religion_show',
            ],
            [
                'id'    => 69,
                'title' => 'religion_delete',
            ],
            [
                'id'    => 70,
                'title' => 'religion_access',
            ],
            [
                'id'    => 71,
                'title' => 'category_create',
            ],
            [
                'id'    => 72,
                'title' => 'category_edit',
            ],
            [
                'id'    => 73,
                'title' => 'category_show',
            ],
            [
                'id'    => 74,
                'title' => 'category_delete',
            ],
            [
                'id'    => 75,
                'title' => 'category_access',
            ],
            [
                'id'    => 76,
                'title' => 'caste_create',
            ],
            [
                'id'    => 77,
                'title' => 'caste_edit',
            ],
            [
                'id'    => 78,
                'title' => 'caste_show',
            ],
            [
                'id'    => 79,
                'title' => 'caste_delete',
            ],
            [
                'id'    => 80,
                'title' => 'caste_access',
            ],
            [
                'id'    => 81,
                'title' => 'country_create',
            ],
            [
                'id'    => 82,
                'title' => 'country_edit',
            ],
            [
                'id'    => 83,
                'title' => 'country_show',
            ],
            [
                'id'    => 84,
                'title' => 'country_delete',
            ],
            [
                'id'    => 85,
                'title' => 'country_access',
            ],
            [
                'id'    => 86,
                'title' => 'province_create',
            ],
            [
                'id'    => 87,
                'title' => 'province_edit',
            ],
            [
                'id'    => 88,
                'title' => 'province_show',
            ],
            [
                'id'    => 89,
                'title' => 'province_delete',
            ],
            [
                'id'    => 90,
                'title' => 'province_access',
            ],
            [
                'id'    => 91,
                'title' => 'postal_code_create',
            ],
            [
                'id'    => 92,
                'title' => 'postal_code_edit',
            ],
            [
                'id'    => 93,
                'title' => 'postal_code_show',
            ],
            [
                'id'    => 94,
                'title' => 'postal_code_delete',
            ],
            [
                'id'    => 95,
                'title' => 'postal_code_access',
            ],
            [
                'id'    => 96,
                'title' => 'photo_create',
            ],
            [
                'id'    => 97,
                'title' => 'photo_edit',
            ],
            [
                'id'    => 98,
                'title' => 'photo_show',
            ],
            [
                'id'    => 99,
                'title' => 'photo_delete',
            ],
            [
                'id'    => 100,
                'title' => 'photo_access',
            ],
            [
                'id'    => 101,
                'title' => 'adress_create',
            ],
            [
                'id'    => 102,
                'title' => 'adress_edit',
            ],
            [
                'id'    => 103,
                'title' => 'adress_show',
            ],
            [
                'id'    => 104,
                'title' => 'adress_delete',
            ],
            [
                'id'    => 105,
                'title' => 'adress_access',
            ],
            [
                'id'    => 106,
                'title' => 'qualification_level_create',
            ],
            [
                'id'    => 107,
                'title' => 'qualification_level_edit',
            ],
            [
                'id'    => 108,
                'title' => 'qualification_level_show',
            ],
            [
                'id'    => 109,
                'title' => 'qualification_level_delete',
            ],
            [
                'id'    => 110,
                'title' => 'qualification_level_access',
            ],
            [
                'id'    => 111,
                'title' => 'board_create',
            ],
            [
                'id'    => 112,
                'title' => 'board_edit',
            ],
            [
                'id'    => 113,
                'title' => 'board_show',
            ],
            [
                'id'    => 114,
                'title' => 'board_delete',
            ],
            [
                'id'    => 115,
                'title' => 'board_access',
            ],
            [
                'id'    => 116,
                'title' => 'academic_qualification_create',
            ],
            [
                'id'    => 117,
                'title' => 'academic_qualification_edit',
            ],
            [
                'id'    => 118,
                'title' => 'academic_qualification_show',
            ],
            [
                'id'    => 119,
                'title' => 'academic_qualification_delete',
            ],
            [
                'id'    => 120,
                'title' => 'academic_qualification_access',
            ],
            [
                'id'    => 121,
                'title' => 'eligibility_test_create',
            ],
            [
                'id'    => 122,
                'title' => 'eligibility_test_edit',
            ],
            [
                'id'    => 123,
                'title' => 'eligibility_test_show',
            ],
            [
                'id'    => 124,
                'title' => 'eligibility_test_delete',
            ],
            [
                'id'    => 125,
                'title' => 'eligibility_test_access',
            ],
            [
                'id'    => 126,
                'title' => 'employment_history_create',
            ],
            [
                'id'    => 127,
                'title' => 'employment_history_edit',
            ],
            [
                'id'    => 128,
                'title' => 'employment_history_show',
            ],
            [
                'id'    => 129,
                'title' => 'employment_history_delete',
            ],
            [
                'id'    => 130,
                'title' => 'employment_history_access',
            ],
            [
                'id'    => 131,
                'title' => 'setting_access',
            ],
            [
                'id'    => 132,
                'title' => 'foreign_visit_create',
            ],
            [
                'id'    => 133,
                'title' => 'foreign_visit_edit',
            ],
            [
                'id'    => 134,
                'title' => 'foreign_visit_show',
            ],
            [
                'id'    => 135,
                'title' => 'foreign_visit_delete',
            ],
            [
                'id'    => 136,
                'title' => 'foreign_visit_access',
            ],
            [
                'id'    => 137,
                'title' => 'referee_create',
            ],
            [
                'id'    => 138,
                'title' => 'referee_edit',
            ],
            [
                'id'    => 139,
                'title' => 'referee_show',
            ],
            [
                'id'    => 140,
                'title' => 'referee_delete',
            ],
            [
                'id'    => 141,
                'title' => 'referee_access',
            ],
            [
                'id'    => 142,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
