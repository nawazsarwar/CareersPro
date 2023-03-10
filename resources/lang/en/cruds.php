<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                        => 'ID',
            'id_helper'                 => ' ',
            'name'                      => 'Name',
            'name_helper'               => ' ',
            'email'                     => 'Email',
            'email_helper'              => ' ',
            'email_verified_at'         => 'Email verified at',
            'email_verified_at_helper'  => ' ',
            'password'                  => 'Password',
            'password_helper'           => ' ',
            'roles'                     => 'Roles',
            'roles_helper'              => ' ',
            'remember_token'            => 'Remember Token',
            'remember_token_helper'     => ' ',
            'created_at'                => 'Created at',
            'created_at_helper'         => ' ',
            'updated_at'                => 'Updated at',
            'updated_at_helper'         => ' ',
            'deleted_at'                => 'Deleted at',
            'deleted_at_helper'         => ' ',
            'verified'                  => 'Verified',
            'verified_helper'           => ' ',
            'verified_at'               => 'Verified at',
            'verified_at_helper'        => ' ',
            'verification_token'        => 'Verification token',
            'verification_token_helper' => ' ',
        ],
    ],
    'career' => [
        'title'          => 'Careers',
        'title_singular' => 'Career',
    ],
    'advertisement' => [
        'title'          => 'Advertisements',
        'title_singular' => 'Advertisement',
        'fields'         => [
            'id'                              => 'ID',
            'id_helper'                       => ' ',
            'title'                           => 'Title',
            'title_helper'                    => ' ',
            'slug'                            => 'Slug',
            'slug_helper'                     => ' ',
            'description'                     => 'Description',
            'description_helper'              => ' ',
            'dated'                           => 'Dated',
            'dated_helper'                    => ' ',
            'advertisement_url'               => 'Advertisement Url',
            'advertisement_url_helper'        => ' ',
            'document'                        => 'Document',
            'document_helper'                 => ' ',
            'default_fee'                     => 'Default Fee',
            'default_fee_helper'              => ' ',
            'default_open_date'               => 'Default Open Date',
            'default_open_date_helper'        => ' ',
            'default_end_date'                => 'Default End Date',
            'default_end_date_helper'         => ' ',
            'default_payment_end_date'        => 'Default Payment End Date',
            'default_payment_end_date_helper' => ' ',
            'approved_at'                     => 'Approved At',
            'approved_at_helper'              => ' ',
            'status'                          => 'Status',
            'status_helper'                   => ' ',
            'remarks'                         => 'Remarks',
            'remarks_helper'                  => ' ',
            'added_by'                        => 'Added By',
            'added_by_helper'                 => ' ',
            'approved_by'                     => 'Approved By',
            'approved_by_helper'              => ' ',
            'created_at'                      => 'Created at',
            'created_at_helper'               => ' ',
            'updated_at'                      => 'Updated at',
            'updated_at_helper'               => ' ',
            'deleted_at'                      => 'Deleted at',
            'deleted_at_helper'               => ' ',
            'type'                            => 'Type',
            'type_helper'                     => ' ',
        ],
    ],
    'advertisementType' => [
        'title'          => 'Advertisement Types',
        'title_singular' => 'Advertisement Type',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'postType' => [
        'title'          => 'Post Types',
        'title_singular' => 'Post Type',
        'fields'         => [
            'id'                      => 'ID',
            'id_helper'               => ' ',
            'name'                    => 'Name',
            'name_helper'             => ' ',
            'pdf_template'            => 'Pdf Template',
            'pdf_template_helper'     => ' ',
            'submission_venue'        => 'Submission Venue',
            'submission_venue_helper' => ' ',
            'status'                  => 'Status',
            'status_helper'           => ' ',
            'remarks'                 => 'Remarks',
            'remarks_helper'          => ' ',
            'created_at'              => 'Created at',
            'created_at_helper'       => ' ',
            'updated_at'              => 'Updated at',
            'updated_at_helper'       => ' ',
            'deleted_at'              => 'Deleted at',
            'deleted_at_helper'       => ' ',
        ],
    ],
    'post' => [
        'title'          => 'Posts',
        'title_singular' => 'Post',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => ' ',
            'advertisement'            => 'Advertisement',
            'advertisement_helper'     => ' ',
            'posttype'                 => 'Posttype',
            'posttype_helper'          => ' ',
            'serial_no'                => 'Serial No',
            'serial_no_helper'         => ' ',
            'title'                    => 'Title',
            'title_helper'             => ' ',
            'slug'                     => 'Slug',
            'slug_helper'              => ' ',
            'description'              => 'Description',
            'description_helper'       => ' ',
            'vacancies'                => 'Vacancies',
            'vacancies_helper'         => ' ',
            'location'                 => 'Location',
            'location_helper'          => ' ',
            'pay_level'                => 'Pay Level',
            'pay_level_helper'         => ' ',
            'pay_range'                => 'Pay Range',
            'pay_range_helper'         => ' ',
            'fee'                      => 'Fee',
            'fee_helper'               => ' ',
            'open_date'                => 'Open Date',
            'open_date_helper'         => ' ',
            'last_date'                => 'Last Date',
            'last_date_helper'         => ' ',
            'payment_last_date'        => 'Payment Last Date',
            'payment_last_date_helper' => ' ',
            'withdrawn'                => 'Withdrawn',
            'withdrawn_helper'         => ' ',
            'status'                   => 'Status',
            'status_helper'            => ' ',
            'remarks'                  => 'Remarks',
            'remarks_helper'           => ' ',
            'added_by'                 => 'Added By',
            'added_by_helper'          => ' ',
            'created_at'               => 'Created at',
            'created_at_helper'        => ' ',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => ' ',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => ' ',
        ],
    ],
    'auditLog' => [
        'title'          => 'Audit Logs',
        'title_singular' => 'Audit Log',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'description'         => 'Description',
            'description_helper'  => ' ',
            'subject_id'          => 'Subject ID',
            'subject_id_helper'   => ' ',
            'subject_type'        => 'Subject Type',
            'subject_type_helper' => ' ',
            'user_id'             => 'User ID',
            'user_id_helper'      => ' ',
            'properties'          => 'Properties',
            'properties_helper'   => ' ',
            'host'                => 'Host',
            'host_helper'         => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
        ],
    ],
    'faqManagement' => [
        'title'          => 'FAQ Management',
        'title_singular' => 'FAQ Management',
    ],
    'faqCategory' => [
        'title'          => 'Categories',
        'title_singular' => 'Category',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'category'          => 'Category',
            'category_helper'   => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => ' ',
        ],
    ],
    'faqQuestion' => [
        'title'          => 'Questions',
        'title_singular' => 'Question',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'category'          => 'Category',
            'category_helper'   => ' ',
            'question'          => 'Question',
            'question_helper'   => ' ',
            'answer'            => 'Answer',
            'answer_helper'     => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => ' ',
        ],
    ],
    'profile' => [
        'title'          => 'Profiles',
        'title_singular' => 'Profile',
        'fields'         => [
            'id'                        => 'ID',
            'id_helper'                 => ' ',
            'user'                      => 'User',
            'user_helper'               => ' ',
            'first_name'                => 'First Name',
            'first_name_helper'         => ' ',
            'middle_name'               => 'Middle Name',
            'middle_name_helper'        => ' ',
            'last_name'                 => 'Last Name',
            'last_name_helper'          => ' ',
            'spouse_name'               => 'Spouse Name',
            'spouse_name_helper'        => ' ',
            'created_at'                => 'Created at',
            'created_at_helper'         => ' ',
            'updated_at'                => 'Updated at',
            'updated_at_helper'         => ' ',
            'deleted_at'                => 'Deleted at',
            'deleted_at_helper'         => ' ',
            'marital_status'            => 'Marital Status',
            'marital_status_helper'     => ' ',
            'fathers_name'              => 'Fathers Name',
            'fathers_name_helper'       => ' ',
            'mothers_name'              => 'Mothers Name',
            'mothers_name_helper'       => ' ',
            'dob'                       => 'Dob',
            'dob_helper'                => ' ',
            'gender'                    => 'Gender',
            'gender_helper'             => ' ',
            'mobile'                    => 'Mobile',
            'mobile_helper'             => ' ',
            'mobile_verified_at'        => 'Mobile Verified At',
            'mobile_verified_at_helper' => ' ',
            'alternate_mobile'          => 'Alternate Mobile',
            'alternate_mobile_helper'   => ' ',
            'pwd'                       => 'PwD',
            'pwd_helper'                => ' ',
            'disability_type'           => 'Disability Type',
            'disability_type_helper'    => ' ',
            'disability_percent'        => 'Disability Percent',
            'disability_percent_helper' => ' ',
            'aadhaar_no'                => 'Aadhaar No',
            'aadhaar_no_helper'         => ' ',
            'religion'                  => 'Religion',
            'religion_helper'           => ' ',
            'category'                  => 'Category',
            'category_helper'           => ' ',
            'caste'                     => 'Caste',
            'caste_helper'              => ' ',
            'sub_caste'                 => 'Sub Caste',
            'sub_caste_helper'          => ' ',
            'place_of_birth'            => 'Place Of Birth',
            'place_of_birth_helper'     => ' ',
            'identity_marks'            => 'Identity Marks',
            'identity_marks_helper'     => ' ',
            'remarks'                   => 'Remarks',
            'remarks_helper'            => ' ',
            'verified'                  => 'Verified',
            'verified_helper'           => ' ',
            'locked'                    => 'Locked',
            'locked_helper'             => ' ',
            'conviction'                => 'Have you been ever been arrested/prosecuted/kept in detention/bound down/fined convicted by a court of Law or whether any case is pending against you in a Court of Law?',
            'conviction_helper'         => ' ',
            'conviction_reason'         => 'Conviction Reason',
            'conviction_reason_helper'  => ' ',
            'debarred'                  => 'Have you ever been debarred from any exam/rusticated by any University or any other educational institution or whether any case is pending against you in any University or any other educational institution?',
            'debarred_helper'           => ' ',
            'debarred_reason'           => 'Debarred Reason',
            'debarred_reason_helper'    => ' ',
            'vigilance'                 => 'Any vigilance/Disciplinary case is pending against you?',
            'vigilance_helper'          => ' ',
            'vigilance_reason'          => 'Vigilance Reason',
            'vigilance_reason_helper'   => ' ',
            'nationality'               => 'Nationality',
            'nationality_helper'        => ' ',
            'district_of_birth'         => 'District Of Birth',
            'district_of_birth_helper'  => ' ',
            'state_of_birth'            => 'State Of Birth',
            'state_of_birth_helper'     => ' ',
            'domicile_state'            => 'Domicile State',
            'domicile_state_helper'     => ' ',
            'domicile_district'         => 'Domicile District',
            'domicile_district_helper'  => ' ',
        ],
    ],
    'maritalStatus' => [
        'title'          => 'Marital Statuses',
        'title_singular' => 'Marital Status',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'disabilityType' => [
        'title'          => 'Disability Types',
        'title_singular' => 'Disability Type',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'religion' => [
        'title'          => 'Religions',
        'title_singular' => 'Religion',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'category' => [
        'title'          => 'Categories',
        'title_singular' => 'Category',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'caste' => [
        'title'          => 'Castes',
        'title_singular' => 'Caste',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'country' => [
        'title'          => 'Countries',
        'title_singular' => 'Country',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'short_code'        => 'Short Code',
            'short_code_helper' => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'province' => [
        'title'          => 'Provinces',
        'title_singular' => 'Province',
        'fields'         => [
            'id'                                   => 'ID',
            'id_helper'                            => ' ',
            'type'                                 => 'Type',
            'type_helper'                          => ' ',
            'name'                                 => 'Name',
            'name_helper'                          => ' ',
            'iso_3166_2_in'                        => 'Iso 3166 2 In',
            'iso_3166_2_in_helper'                 => ' ',
            'vehicle_code'                         => 'Vehicle Code',
            'vehicle_code_helper'                  => ' ',
            'zone'                                 => 'Zone',
            'zone_helper'                          => ' ',
            'capital'                              => 'Capital',
            'capital_helper'                       => ' ',
            'largest_city'                         => 'Largest City',
            'largest_city_helper'                  => ' ',
            'statehood'                            => 'Statehood',
            'statehood_helper'                     => ' ',
            'population'                           => 'Population',
            'population_helper'                    => ' ',
            'area'                                 => 'Area',
            'area_helper'                          => 'Area in (km2)',
            'official_languages'                   => 'Official Languages',
            'official_languages_helper'            => ' ',
            'additional_official_languages'        => 'Additional Official Languages',
            'additional_official_languages_helper' => ' ',
            'created_at'                           => 'Created at',
            'created_at_helper'                    => ' ',
            'updated_at'                           => 'Updated at',
            'updated_at_helper'                    => ' ',
            'deleted_at'                           => 'Deleted at',
            'deleted_at_helper'                    => ' ',
        ],
    ],
    'postalCode' => [
        'title'          => 'Postal Codes',
        'title_singular' => 'Postal Code',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'name'                => 'Name',
            'name_helper'         => ' ',
            'locality'            => 'Locality',
            'locality_helper'     => ' ',
            'code'                => 'Code',
            'code_helper'         => ' ',
            'sub_district'        => 'Sub District',
            'sub_district_helper' => ' ',
            'district'            => 'District',
            'district_helper'     => ' ',
            'province'            => 'Province',
            'province_helper'     => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
        ],
    ],
    'photo' => [
        'title'          => 'Photos',
        'title_singular' => 'Photo',
        'fields'         => [
            'id'                      => 'ID',
            'id_helper'               => ' ',
            'photograph'              => 'Photograph',
            'photograph_helper'       => ' ',
            'signature'               => 'Signature',
            'signature_helper'        => ' ',
            'thumb_impression'        => 'Thumb Impression',
            'thumb_impression_helper' => ' ',
            'created_at'              => 'Created at',
            'created_at_helper'       => ' ',
            'updated_at'              => 'Updated at',
            'updated_at_helper'       => ' ',
            'deleted_at'              => 'Deleted at',
            'deleted_at_helper'       => ' ',
            'user'                    => 'User',
            'user_helper'             => ' ',
        ],
    ],
    'adress' => [
        'title'          => 'Adresses',
        'title_singular' => 'Adress',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'type'               => 'Type',
            'type_helper'        => ' ',
            'house_no'           => 'House No',
            'house_no_helper'    => ' ',
            'street'             => 'Street',
            'street_helper'      => ' ',
            'landmark'           => 'Landmark',
            'landmark_helper'    => ' ',
            'locality'           => 'Locality',
            'locality_helper'    => ' ',
            'city'               => 'City',
            'city_helper'        => ' ',
            'postal_code'        => 'Postal Code',
            'postal_code_helper' => ' ',
            'district'           => 'District',
            'district_helper'    => ' ',
            'province'           => 'Province',
            'province_helper'    => ' ',
            'country'            => 'Country',
            'country_helper'     => ' ',
            'status'             => 'Status',
            'status_helper'      => ' ',
            'remarks'            => 'Remarks',
            'remarks_helper'     => ' ',
            'user'               => 'User',
            'user_helper'        => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'qualificationLevel' => [
        'title'          => 'Qualification Levels',
        'title_singular' => 'Qualification Level',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'value'             => 'Value',
            'value_helper'      => ' ',
            'status'            => 'Status',
            'status_helper'     => ' ',
            'remarks'           => 'Remarks',
            'remarks_helper'    => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'board' => [
        'title'          => 'Boards',
        'title_singular' => 'Board',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'academicQualification' => [
        'title'          => 'Academic Qualifications',
        'title_singular' => 'Academic Qualification',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'course'            => 'Course',
            'course_helper'     => ' ',
            'board'             => 'Board',
            'board_helper'      => ' ',
            'year'              => 'Year of Passing',
            'year_helper'       => ' ',
            'division'          => 'Division',
            'division_helper'   => ' ',
            'percentage'        => 'Percentage',
            'percentage_helper' => 'If the course marking is CPGA based, Enter NA',
            'cgpa'              => 'CGPA',
            'cgpa_helper'       => 'If the course marking is percentage based, Enter NA.',
            'subjects'          => 'Subject(s)',
            'subjects_helper'   => ' ',
            'title'             => 'Research Topic Title',
            'title_helper'      => 'If no research is applicable with the course, Enter NA.',
            'remarks'           => 'Remarks',
            'remarks_helper'    => ' ',
            'document'          => 'Upload Supporting Document',
            'document_helper'   => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
        ],
    ],
    'eligibilityTest' => [
        'title'          => 'Eligibility Tests',
        'title_singular' => 'Eligibility Test',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'name'              => 'Name of the Test',
            'name_helper'       => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'agency'            => 'Agency',
            'agency_helper'     => 'Limit the above input to 150 characters only.',
            'year'              => 'Year',
            'year_helper'       => ' ',
            'subject'           => 'Subject',
            'subject_helper'    => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
        ],
    ],
    'employmentHistory' => [
        'title'          => 'Employment History',
        'title_singular' => 'Employment History',
        'fields'         => [
            'id'                        => 'ID',
            'id_helper'                 => ' ',
            'employer'                  => 'Name of the Employer',
            'employer_helper'           => ' ',
            'type'                      => 'Status of the Organization (Government/Private)',
            'type_helper'               => ' ',
            'created_at'                => 'Created at',
            'created_at_helper'         => ' ',
            'updated_at'                => 'Updated at',
            'updated_at_helper'         => ' ',
            'deleted_at'                => 'Deleted at',
            'deleted_at_helper'         => ' ',
            'designation'               => 'Designation',
            'designation_helper'        => ' ',
            'from'                      => 'Period From',
            'from_helper'               => ' ',
            'to'                        => 'To',
            'to_helper'                 => 'Leave blank if your are currently working on this position',
            'responsibilities'          => 'Nature of Work/Duties',
            'responsibilities_helper'   => 'Limit the above input to 500 characters only.',
            'reason_for_leaving'        => 'Reason For Leaving',
            'reason_for_leaving_helper' => ' ',
            'pay_band'                  => 'Pay Band/AGP',
            'pay_band_helper'           => ' ',
            'basic_pay'                 => 'Basic Pay',
            'basic_pay_helper'          => ' ',
            'gross_pay'                 => 'Gross Pay/Total salary P.M',
            'gross_pay_helper'          => ' ',
            'user'                      => 'user',
            'user_helper'               => ' ',
        ],
    ],
    'setting' => [
        'title'          => 'Settings',
        'title_singular' => 'Setting',
    ],
    'foreignVisit' => [
        'title'          => 'Foreign Visits',
        'title_singular' => 'Foreign Visit',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'country'           => 'Country',
            'country_helper'    => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
            'date'              => 'Date of Travel',
            'date_helper'       => ' ',
            'duration'          => 'Duration',
            'duration_helper'   => 'In days',
            'purpose'           => 'Purpose',
            'purpose_helper'    => ' ',
            'user'              => 'User',
            'user_helper'       => ' ',
        ],
    ],
    'referee' => [
        'title'          => 'Referees',
        'title_singular' => 'Referee',
        'fields'         => [
            'id'                  => 'ID',
            'id_helper'           => ' ',
            'name'                => 'Name',
            'name_helper'         => ' ',
            'designation'         => 'Designation',
            'designation_helper'  => ' ',
            'mobile'              => 'Mobile No',
            'mobile_helper'       => ' ',
            'email'               => 'Email',
            'email_helper'        => ' ',
            'address'             => 'Address',
            'address_helper'      => ' ',
            'period_known'        => 'Period Known',
            'period_known_helper' => ' ',
            'created_at'          => 'Created at',
            'created_at_helper'   => ' ',
            'updated_at'          => 'Updated at',
            'updated_at_helper'   => ' ',
            'deleted_at'          => 'Deleted at',
            'deleted_at_helper'   => ' ',
        ],
    ],
];
