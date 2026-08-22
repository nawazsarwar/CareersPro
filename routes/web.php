<?php

use App\Http\Controllers\Admin\AcademicQualificationsController as AdminAcademicQualificationsController;
use App\Http\Controllers\Admin\AdressesController as AdminAdressesController;
use App\Http\Controllers\Admin\AdvertisementTypesController as AdminAdvertisementTypesController;
use App\Http\Controllers\Admin\AdvertisementsController as AdminAdvertisementsController;
use App\Http\Controllers\Admin\ApplicationFormsController as AdminApplicationFormsController;
use App\Http\Controllers\Admin\AuditLogsController as AdminAuditLogsController;
use App\Http\Controllers\Admin\BoardsController as AdminBoardsController;
use App\Http\Controllers\Admin\CastesController as AdminCastesController;
use App\Http\Controllers\Admin\CategoriesController as AdminCategoriesController;
use App\Http\Controllers\Admin\CountriesController as AdminCountriesController;
use App\Http\Controllers\Admin\DisabilityTypesController as AdminDisabilityTypesController;
use App\Http\Controllers\Admin\EligibilityTestsController as AdminEligibilityTestsController;
use App\Http\Controllers\Admin\EmploymentHistoryController as AdminEmploymentHistoryController;
use App\Http\Controllers\Admin\FaqCategoryController as AdminFaqCategoryController;
use App\Http\Controllers\Admin\FaqQuestionController as AdminFaqQuestionController;
use App\Http\Controllers\Admin\ForeignVisitsController as AdminForeignVisitsController;
use App\Http\Controllers\Admin\GlobalSearchController as AdminGlobalSearchController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\InstitutionsAttendedController as AdminInstitutionsAttendedController;
use App\Http\Controllers\Admin\MaritalStatusesController as AdminMaritalStatusesController;
use App\Http\Controllers\Admin\OtherDetailsController as AdminOtherDetailsController;
use App\Http\Controllers\Admin\PermissionsController as AdminPermissionsController;
use App\Http\Controllers\Admin\PhotosController as AdminPhotosController;
use App\Http\Controllers\Admin\PostTypesController as AdminPostTypesController;
use App\Http\Controllers\Admin\PostalCodesController as AdminPostalCodesController;
use App\Http\Controllers\Admin\PostsController as AdminPostsController;
use App\Http\Controllers\Admin\ProfilesController as AdminProfilesController;
use App\Http\Controllers\Admin\ProvincesController as AdminProvincesController;
use App\Http\Controllers\Admin\QualificationLevelsController as AdminQualificationLevelsController;
use App\Http\Controllers\Admin\RefereesController as AdminRefereesController;
use App\Http\Controllers\Admin\ReligionsController as AdminReligionsController;
use App\Http\Controllers\Admin\RolesController as AdminRolesController;
use App\Http\Controllers\Admin\TraedController as AdminTraedController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Auth\ChangePasswordController as AuthChangePasswordController;
use App\Http\Controllers\Frontend\AcademicQualificationsController as FrontendAcademicQualificationsController;
use App\Http\Controllers\Frontend\AdressesController as FrontendAdressesController;
use App\Http\Controllers\Frontend\AdvertisementTypesController as FrontendAdvertisementTypesController;
use App\Http\Controllers\Frontend\AdvertisementsController as FrontendAdvertisementsController;
use App\Http\Controllers\Frontend\ApplicationFormsController as FrontendApplicationFormsController;
use App\Http\Controllers\Frontend\BoardsController as FrontendBoardsController;
use App\Http\Controllers\Frontend\CastesController as FrontendCastesController;
use App\Http\Controllers\Frontend\CategoriesController as FrontendCategoriesController;
use App\Http\Controllers\Frontend\CountriesController as FrontendCountriesController;
use App\Http\Controllers\Frontend\DisabilityTypesController as FrontendDisabilityTypesController;
use App\Http\Controllers\Frontend\EligibilityTestsController as FrontendEligibilityTestsController;
use App\Http\Controllers\Frontend\EmploymentHistoryController as FrontendEmploymentHistoryController;
use App\Http\Controllers\Frontend\FaqCategoryController as FrontendFaqCategoryController;
use App\Http\Controllers\Frontend\FaqQuestionController as FrontendFaqQuestionController;
use App\Http\Controllers\Frontend\ForeignVisitsController as FrontendForeignVisitsController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\InstitutionsAttendedController as FrontendInstitutionsAttendedController;
use App\Http\Controllers\Frontend\MaritalStatusesController as FrontendMaritalStatusesController;
use App\Http\Controllers\Frontend\OtherDetailsController as FrontendOtherDetailsController;
use App\Http\Controllers\Frontend\PermissionsController as FrontendPermissionsController;
use App\Http\Controllers\Frontend\PhotosController as FrontendPhotosController;
use App\Http\Controllers\Frontend\PostTypesController as FrontendPostTypesController;
use App\Http\Controllers\Frontend\PostalCodesController as FrontendPostalCodesController;
use App\Http\Controllers\Frontend\PostsController as FrontendPostsController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use App\Http\Controllers\Frontend\ProfilesController as FrontendProfilesController;
use App\Http\Controllers\Frontend\ProvincesController as FrontendProvincesController;
use App\Http\Controllers\Frontend\QualificationLevelsController as FrontendQualificationLevelsController;
use App\Http\Controllers\Frontend\RefereesController as FrontendRefereesController;
use App\Http\Controllers\Frontend\ReligionsController as FrontendReligionsController;
use App\Http\Controllers\Frontend\RolesController as FrontendRolesController;
use App\Http\Controllers\Frontend\TraedController as FrontendTraedController;
use App\Http\Controllers\Frontend\UsersController as FrontendUsersController;
use App\Http\Controllers\UserVerificationController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::get('userVerification/{token}', [UserVerificationController::class, 'approve'])->name('userVerification');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');

    // Permissions
    Route::delete('permissions/destroy', [AdminPermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', AdminPermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [AdminRolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', AdminRolesController::class);

    // Users
    Route::delete('users/destroy', [AdminUsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', AdminUsersController::class);

    // Audit Logs
    Route::resource('audit-logs', AdminAuditLogsController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Faq Category
    Route::delete('faq-categories/destroy', [AdminFaqCategoryController::class, 'massDestroy'])->name('faq-categories.massDestroy');
    Route::resource('faq-categories', AdminFaqCategoryController::class);

    // Faq Question
    Route::delete('faq-questions/destroy', [AdminFaqQuestionController::class, 'massDestroy'])->name('faq-questions.massDestroy');
    Route::resource('faq-questions', AdminFaqQuestionController::class);

    // Advertisements
    Route::delete('advertisements/destroy', [AdminAdvertisementsController::class, 'massDestroy'])->name('advertisements.massDestroy');
    Route::post('advertisements/media', [AdminAdvertisementsController::class, 'storeMedia'])->name('advertisements.storeMedia');
    Route::post('advertisements/ckmedia', [AdminAdvertisementsController::class, 'storeCKEditorImages'])->name('advertisements.storeCKEditorImages');
    Route::resource('advertisements', AdminAdvertisementsController::class);

    // Advertisement Types
    Route::delete('advertisement-types/destroy', [AdminAdvertisementTypesController::class, 'massDestroy'])->name('advertisement-types.massDestroy');
    Route::resource('advertisement-types', AdminAdvertisementTypesController::class);

    // Post Types
    Route::delete('post-types/destroy', [AdminPostTypesController::class, 'massDestroy'])->name('post-types.massDestroy');
    Route::resource('post-types', AdminPostTypesController::class);

    // Posts
    Route::delete('posts/destroy', [AdminPostsController::class, 'massDestroy'])->name('posts.massDestroy');
    Route::post('posts/media', [AdminPostsController::class, 'storeMedia'])->name('posts.storeMedia');
    Route::post('posts/ckmedia', [AdminPostsController::class, 'storeCKEditorImages'])->name('posts.storeCKEditorImages');
    Route::resource('posts', AdminPostsController::class);

    // Marital Statuses
    Route::delete('marital-statuses/destroy', [AdminMaritalStatusesController::class, 'massDestroy'])->name('marital-statuses.massDestroy');
    Route::resource('marital-statuses', AdminMaritalStatusesController::class);

    // Disability Types
    Route::delete('disability-types/destroy', [AdminDisabilityTypesController::class, 'massDestroy'])->name('disability-types.massDestroy');
    Route::resource('disability-types', AdminDisabilityTypesController::class);

    // Religions
    Route::delete('religions/destroy', [AdminReligionsController::class, 'massDestroy'])->name('religions.massDestroy');
    Route::resource('religions', AdminReligionsController::class);

    // Categories
    Route::delete('categories/destroy', [AdminCategoriesController::class, 'massDestroy'])->name('categories.massDestroy');
    Route::resource('categories', AdminCategoriesController::class);

    // Castes
    Route::delete('castes/destroy', [AdminCastesController::class, 'massDestroy'])->name('castes.massDestroy');
    Route::resource('castes', AdminCastesController::class);

    // Countries
    Route::delete('countries/destroy', [AdminCountriesController::class, 'massDestroy'])->name('countries.massDestroy');
    Route::resource('countries', AdminCountriesController::class);

    // Provinces
    Route::delete('provinces/destroy', [AdminProvincesController::class, 'massDestroy'])->name('provinces.massDestroy');
    Route::resource('provinces', AdminProvincesController::class);

    // Postal Codes
    Route::delete('postal-codes/destroy', [AdminPostalCodesController::class, 'massDestroy'])->name('postal-codes.massDestroy');
    Route::resource('postal-codes', AdminPostalCodesController::class);

    // Profiles
    Route::delete('profiles/destroy', [AdminProfilesController::class, 'massDestroy'])->name('profiles.massDestroy');
    Route::resource('profiles', AdminProfilesController::class);

    // Photos
    Route::delete('photos/destroy', [AdminPhotosController::class, 'massDestroy'])->name('photos.massDestroy');
    Route::post('photos/media', [AdminPhotosController::class, 'storeMedia'])->name('photos.storeMedia');
    Route::post('photos/ckmedia', [AdminPhotosController::class, 'storeCKEditorImages'])->name('photos.storeCKEditorImages');
    Route::resource('photos', AdminPhotosController::class);

    // Adresses
    Route::delete('adresses/destroy', [AdminAdressesController::class, 'massDestroy'])->name('adresses.massDestroy');
    Route::resource('adresses', AdminAdressesController::class);

    // Qualification Levels
    Route::delete('qualification-levels/destroy', [AdminQualificationLevelsController::class, 'massDestroy'])->name('qualification-levels.massDestroy');
    Route::resource('qualification-levels', AdminQualificationLevelsController::class);

    // Boards
    Route::delete('boards/destroy', [AdminBoardsController::class, 'massDestroy'])->name('boards.massDestroy');
    Route::resource('boards', AdminBoardsController::class);

    // Academic Qualifications
    Route::delete('academic-qualifications/destroy', [AdminAcademicQualificationsController::class, 'massDestroy'])->name('academic-qualifications.massDestroy');
    Route::post('academic-qualifications/media', [AdminAcademicQualificationsController::class, 'storeMedia'])->name('academic-qualifications.storeMedia');
    Route::post('academic-qualifications/ckmedia', [AdminAcademicQualificationsController::class, 'storeCKEditorImages'])->name('academic-qualifications.storeCKEditorImages');
    Route::resource('academic-qualifications', AdminAcademicQualificationsController::class);

    // Eligibility Tests
    Route::delete('eligibility-tests/destroy', [AdminEligibilityTestsController::class, 'massDestroy'])->name('eligibility-tests.massDestroy');
    Route::resource('eligibility-tests', AdminEligibilityTestsController::class);

    // Employment History
    Route::delete('employment-histories/destroy', [AdminEmploymentHistoryController::class, 'massDestroy'])->name('employment-histories.massDestroy');
    Route::resource('employment-histories', AdminEmploymentHistoryController::class);

    // Foreign Visits
    Route::delete('foreign-visits/destroy', [AdminForeignVisitsController::class, 'massDestroy'])->name('foreign-visits.massDestroy');
    Route::resource('foreign-visits', AdminForeignVisitsController::class);

    // Referees
    Route::delete('referees/destroy', [AdminRefereesController::class, 'massDestroy'])->name('referees.massDestroy');
    Route::resource('referees', AdminRefereesController::class);

    // Application Forms
    Route::delete('application-forms/destroy', [AdminApplicationFormsController::class, 'massDestroy'])->name('application-forms.massDestroy');
    Route::resource('application-forms', AdminApplicationFormsController::class);

    // Institutions Attended
    Route::delete('institutions-attendeds/destroy', [AdminInstitutionsAttendedController::class, 'massDestroy'])->name('institutions-attendeds.massDestroy');
    Route::resource('institutions-attendeds', AdminInstitutionsAttendedController::class);

    // Traed
    Route::delete('traeds/destroy', [AdminTraedController::class, 'massDestroy'])->name('traeds.massDestroy');
    Route::resource('traeds', AdminTraedController::class);

    // Other Details
    Route::delete('other-details/destroy', [AdminOtherDetailsController::class, 'massDestroy'])->name('other-details.massDestroy');
    Route::post('other-details/media', [AdminOtherDetailsController::class, 'storeMedia'])->name('other-details.storeMedia');
    Route::post('other-details/ckmedia', [AdminOtherDetailsController::class, 'storeCKEditorImages'])->name('other-details.storeCKEditorImages');
    Route::resource('other-details', AdminOtherDetailsController::class);

    Route::get('global-search', [AdminGlobalSearchController::class, 'search'])->name('globalSearch');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [AuthChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [AuthChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [AuthChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [AuthChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
    }
});

Route::group(['as' => 'frontend.', 'middleware' => ['auth']], function () {
    Route::get('/home', [FrontendHomeController::class, 'index'])->name('home');

    // Permissions
    Route::delete('permissions/destroy', [FrontendPermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', FrontendPermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [FrontendRolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', FrontendRolesController::class);

    // Users
    Route::delete('users/destroy', [FrontendUsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', FrontendUsersController::class);

    // Faq Category
    Route::delete('faq-categories/destroy', [FrontendFaqCategoryController::class, 'massDestroy'])->name('faq-categories.massDestroy');
    Route::resource('faq-categories', FrontendFaqCategoryController::class);

    // Faq Question
    Route::delete('faq-questions/destroy', [FrontendFaqQuestionController::class, 'massDestroy'])->name('faq-questions.massDestroy');
    Route::resource('faq-questions', FrontendFaqQuestionController::class);

    // Advertisements
    Route::delete('advertisements/destroy', [FrontendAdvertisementsController::class, 'massDestroy'])->name('advertisements.massDestroy');
    Route::post('advertisements/media', [FrontendAdvertisementsController::class, 'storeMedia'])->name('advertisements.storeMedia');
    Route::post('advertisements/ckmedia', [FrontendAdvertisementsController::class, 'storeCKEditorImages'])->name('advertisements.storeCKEditorImages');
    Route::resource('advertisements', FrontendAdvertisementsController::class);

    // Advertisement Types
    Route::delete('advertisement-types/destroy', [FrontendAdvertisementTypesController::class, 'massDestroy'])->name('advertisement-types.massDestroy');
    Route::resource('advertisement-types', FrontendAdvertisementTypesController::class);

    // Post Types
    Route::delete('post-types/destroy', [FrontendPostTypesController::class, 'massDestroy'])->name('post-types.massDestroy');
    Route::resource('post-types', FrontendPostTypesController::class);

    // Posts
    Route::delete('posts/destroy', [FrontendPostsController::class, 'massDestroy'])->name('posts.massDestroy');
    Route::post('posts/media', [FrontendPostsController::class, 'storeMedia'])->name('posts.storeMedia');
    Route::post('posts/ckmedia', [FrontendPostsController::class, 'storeCKEditorImages'])->name('posts.storeCKEditorImages');
    Route::resource('posts', FrontendPostsController::class);

    // Marital Statuses
    Route::delete('marital-statuses/destroy', [FrontendMaritalStatusesController::class, 'massDestroy'])->name('marital-statuses.massDestroy');
    Route::resource('marital-statuses', FrontendMaritalStatusesController::class);

    // Disability Types
    Route::delete('disability-types/destroy', [FrontendDisabilityTypesController::class, 'massDestroy'])->name('disability-types.massDestroy');
    Route::resource('disability-types', FrontendDisabilityTypesController::class);

    // Religions
    Route::delete('religions/destroy', [FrontendReligionsController::class, 'massDestroy'])->name('religions.massDestroy');
    Route::resource('religions', FrontendReligionsController::class);

    // Categories
    Route::delete('categories/destroy', [FrontendCategoriesController::class, 'massDestroy'])->name('categories.massDestroy');
    Route::resource('categories', FrontendCategoriesController::class);

    // Castes
    Route::delete('castes/destroy', [FrontendCastesController::class, 'massDestroy'])->name('castes.massDestroy');
    Route::resource('castes', FrontendCastesController::class);

    // Countries
    Route::delete('countries/destroy', [FrontendCountriesController::class, 'massDestroy'])->name('countries.massDestroy');
    Route::resource('countries', FrontendCountriesController::class);

    // Provinces
    Route::delete('provinces/destroy', [FrontendProvincesController::class, 'massDestroy'])->name('provinces.massDestroy');
    Route::resource('provinces', FrontendProvincesController::class);

    // Postal Codes
    Route::delete('postal-codes/destroy', [FrontendPostalCodesController::class, 'massDestroy'])->name('postal-codes.massDestroy');
    Route::resource('postal-codes', FrontendPostalCodesController::class);

    // Profiles
    Route::delete('profiles/destroy', [FrontendProfilesController::class, 'massDestroy'])->name('profiles.massDestroy');
    Route::resource('profiles', FrontendProfilesController::class);

    // Photos
    Route::delete('photos/destroy', [FrontendPhotosController::class, 'massDestroy'])->name('photos.massDestroy');
    Route::post('photos/media', [FrontendPhotosController::class, 'storeMedia'])->name('photos.storeMedia');
    Route::post('photos/ckmedia', [FrontendPhotosController::class, 'storeCKEditorImages'])->name('photos.storeCKEditorImages');
    Route::resource('photos', FrontendPhotosController::class);

    // Adresses
    Route::delete('adresses/destroy', [FrontendAdressesController::class, 'massDestroy'])->name('adresses.massDestroy');
    Route::resource('adresses', FrontendAdressesController::class);

    // Qualification Levels
    Route::delete('qualification-levels/destroy', [FrontendQualificationLevelsController::class, 'massDestroy'])->name('qualification-levels.massDestroy');
    Route::resource('qualification-levels', FrontendQualificationLevelsController::class);

    // Boards
    Route::delete('boards/destroy', [FrontendBoardsController::class, 'massDestroy'])->name('boards.massDestroy');
    Route::resource('boards', FrontendBoardsController::class);

    // Academic Qualifications
    Route::delete('academic-qualifications/destroy', [FrontendAcademicQualificationsController::class, 'massDestroy'])->name('academic-qualifications.massDestroy');
    Route::post('academic-qualifications/media', [FrontendAcademicQualificationsController::class, 'storeMedia'])->name('academic-qualifications.storeMedia');
    Route::post('academic-qualifications/ckmedia', [FrontendAcademicQualificationsController::class, 'storeCKEditorImages'])->name('academic-qualifications.storeCKEditorImages');
    Route::resource('academic-qualifications', FrontendAcademicQualificationsController::class);

    // Eligibility Tests
    Route::delete('eligibility-tests/destroy', [FrontendEligibilityTestsController::class, 'massDestroy'])->name('eligibility-tests.massDestroy');
    Route::resource('eligibility-tests', FrontendEligibilityTestsController::class);

    // Employment History
    Route::delete('employment-histories/destroy', [FrontendEmploymentHistoryController::class, 'massDestroy'])->name('employment-histories.massDestroy');
    Route::resource('employment-histories', FrontendEmploymentHistoryController::class);

    // Foreign Visits
    Route::delete('foreign-visits/destroy', [FrontendForeignVisitsController::class, 'massDestroy'])->name('foreign-visits.massDestroy');
    Route::resource('foreign-visits', FrontendForeignVisitsController::class);

    // Referees
    Route::delete('referees/destroy', [FrontendRefereesController::class, 'massDestroy'])->name('referees.massDestroy');
    Route::resource('referees', FrontendRefereesController::class);

    // Application Forms
    Route::delete('application-forms/destroy', [FrontendApplicationFormsController::class, 'massDestroy'])->name('application-forms.massDestroy');
    Route::resource('application-forms', FrontendApplicationFormsController::class);

    // Institutions Attended
    Route::delete('institutions-attendeds/destroy', [FrontendInstitutionsAttendedController::class, 'massDestroy'])->name('institutions-attendeds.massDestroy');
    Route::resource('institutions-attendeds', FrontendInstitutionsAttendedController::class);

    // Traed
    Route::delete('traeds/destroy', [FrontendTraedController::class, 'massDestroy'])->name('traeds.massDestroy');
    Route::resource('traeds', FrontendTraedController::class);

    // Other Details
    Route::delete('other-details/destroy', [FrontendOtherDetailsController::class, 'massDestroy'])->name('other-details.massDestroy');
    Route::post('other-details/media', [FrontendOtherDetailsController::class, 'storeMedia'])->name('other-details.storeMedia');
    Route::post('other-details/ckmedia', [FrontendOtherDetailsController::class, 'storeCKEditorImages'])->name('other-details.storeCKEditorImages');
    Route::resource('other-details', FrontendOtherDetailsController::class);

    Route::get('frontend/profile', [FrontendProfileController::class, 'index'])->name('profile.index');
    Route::post('frontend/profile', [FrontendProfileController::class, 'update'])->name('profile.update');
    Route::post('frontend/profile/destroy', [FrontendProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('frontend/profile/password', [FrontendProfileController::class, 'password'])->name('profile.password');
});

require __DIR__.'/auth.php';
