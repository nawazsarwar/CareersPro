<?php

use App\Http\Controllers\Api\V1\Admin\AcademicQualificationsApiController;
use App\Http\Controllers\Api\V1\Admin\AdressesApiController;
use App\Http\Controllers\Api\V1\Admin\AdvertisementTypesApiController;
use App\Http\Controllers\Api\V1\Admin\AdvertisementsApiController;
use App\Http\Controllers\Api\V1\Admin\ApplicationFormsApiController;
use App\Http\Controllers\Api\V1\Admin\BoardsApiController;
use App\Http\Controllers\Api\V1\Admin\CastesApiController;
use App\Http\Controllers\Api\V1\Admin\CategoriesApiController;
use App\Http\Controllers\Api\V1\Admin\CountriesApiController;
use App\Http\Controllers\Api\V1\Admin\DisabilityTypesApiController;
use App\Http\Controllers\Api\V1\Admin\EligibilityTestsApiController;
use App\Http\Controllers\Api\V1\Admin\EmploymentHistoryApiController;
use App\Http\Controllers\Api\V1\Admin\ForeignVisitsApiController;
use App\Http\Controllers\Api\V1\Admin\InstitutionsAttendedApiController;
use App\Http\Controllers\Api\V1\Admin\MaritalStatusesApiController;
use App\Http\Controllers\Api\V1\Admin\OtherDetailsApiController;
use App\Http\Controllers\Api\V1\Admin\PhotosApiController;
use App\Http\Controllers\Api\V1\Admin\PostTypesApiController;
use App\Http\Controllers\Api\V1\Admin\PostalCodesApiController;
use App\Http\Controllers\Api\V1\Admin\PostsApiController;
use App\Http\Controllers\Api\V1\Admin\ProfilesApiController;
use App\Http\Controllers\Api\V1\Admin\ProvincesApiController;
use App\Http\Controllers\Api\V1\Admin\QualificationLevelsApiController;
use App\Http\Controllers\Api\V1\Admin\RefereesApiController;
use App\Http\Controllers\Api\V1\Admin\ReligionsApiController;
use App\Http\Controllers\Api\V1\Admin\TraedApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    // Advertisements
    Route::post('advertisements/media', [AdvertisementsApiController::class, 'storeMedia'])->name('advertisements.storeMedia');
    Route::apiResource('advertisements', AdvertisementsApiController::class);

    // Advertisement Types
    Route::apiResource('advertisement-types', AdvertisementTypesApiController::class);

    // Post Types
    Route::apiResource('post-types', PostTypesApiController::class);

    // Posts
    Route::post('posts/media', [PostsApiController::class, 'storeMedia'])->name('posts.storeMedia');
    Route::apiResource('posts', PostsApiController::class);

    // Marital Statuses
    Route::apiResource('marital-statuses', MaritalStatusesApiController::class);

    // Disability Types
    Route::apiResource('disability-types', DisabilityTypesApiController::class);

    // Religions
    Route::apiResource('religions', ReligionsApiController::class);

    // Categories
    Route::apiResource('categories', CategoriesApiController::class);

    // Castes
    Route::apiResource('castes', CastesApiController::class);

    // Countries
    Route::apiResource('countries', CountriesApiController::class);

    // Provinces
    Route::apiResource('provinces', ProvincesApiController::class);

    // Postal Codes
    Route::apiResource('postal-codes', PostalCodesApiController::class);

    // Profiles
    Route::apiResource('profiles', ProfilesApiController::class);

    // Photos
    Route::post('photos/media', [PhotosApiController::class, 'storeMedia'])->name('photos.storeMedia');
    Route::apiResource('photos', PhotosApiController::class);

    // Adresses
    Route::apiResource('adresses', AdressesApiController::class);

    // Qualification Levels
    Route::apiResource('qualification-levels', QualificationLevelsApiController::class);

    // Boards
    Route::apiResource('boards', BoardsApiController::class);

    // Academic Qualifications
    Route::post('academic-qualifications/media', [AcademicQualificationsApiController::class, 'storeMedia'])->name('academic-qualifications.storeMedia');
    Route::apiResource('academic-qualifications', AcademicQualificationsApiController::class);

    // Eligibility Tests
    Route::apiResource('eligibility-tests', EligibilityTestsApiController::class);

    // Employment History
    Route::apiResource('employment-histories', EmploymentHistoryApiController::class);

    // Foreign Visits
    Route::apiResource('foreign-visits', ForeignVisitsApiController::class);

    // Referees
    Route::apiResource('referees', RefereesApiController::class);

    // Application Forms
    Route::apiResource('application-forms', ApplicationFormsApiController::class);

    // Institutions Attended
    Route::apiResource('institutions-attendeds', InstitutionsAttendedApiController::class);

    // Traed
    Route::apiResource('traeds', TraedApiController::class);

    // Other Details
    Route::post('other-details/media', [OtherDetailsApiController::class, 'storeMedia'])->name('other-details.storeMedia');
    Route::apiResource('other-details', OtherDetailsApiController::class);
});
