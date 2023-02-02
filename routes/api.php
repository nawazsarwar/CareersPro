<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Advertisements
    Route::post('advertisements/media', 'AdvertisementsApiController@storeMedia')->name('advertisements.storeMedia');
    Route::apiResource('advertisements', 'AdvertisementsApiController');

    // Posts
    Route::apiResource('posts', 'PostsApiController');

    // Profiles
    Route::apiResource('profiles', 'ProfilesApiController');

    // Marital Statuses
    Route::apiResource('marital-statuses', 'MaritalStatusesApiController');

    // Disability Types
    Route::apiResource('disability-types', 'DisabilityTypesApiController');

    // Religions
    Route::apiResource('religions', 'ReligionsApiController');

    // Categories
    Route::apiResource('categories', 'CategoriesApiController');

    // Castes
    Route::apiResource('castes', 'CastesApiController');

    // Countries
    Route::apiResource('countries', 'CountriesApiController');

    // Provinces
    Route::apiResource('provinces', 'ProvincesApiController');

    // Postal Codes
    Route::apiResource('postal-codes', 'PostalCodesApiController');

    // Photos
    Route::post('photos/media', 'PhotosApiController@storeMedia')->name('photos.storeMedia');
    Route::apiResource('photos', 'PhotosApiController');

    // Adresses
    Route::apiResource('adresses', 'AdressesApiController');

    // Qualification Levels
    Route::apiResource('qualification-levels', 'QualificationLevelsApiController');

    // Boards
    Route::apiResource('boards', 'BoardsApiController');

    // Academic Qualifications
    Route::post('academic-qualifications/media', 'AcademicQualificationsApiController@storeMedia')->name('academic-qualifications.storeMedia');
    Route::apiResource('academic-qualifications', 'AcademicQualificationsApiController');

    // Eligibility Tests
    Route::apiResource('eligibility-tests', 'EligibilityTestsApiController');

    // Employment History
    Route::apiResource('employment-histories', 'EmploymentHistoryApiController');

    // Foreign Visits
    Route::apiResource('foreign-visits', 'ForeignVisitsApiController');

    // Referees
    Route::apiResource('referees', 'RefereesApiController');
});
