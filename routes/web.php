<?php

Route::view('/', 'welcome');
Route::get('userVerification/{token}', 'UserVerificationController@approve')->name('userVerification');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Advertisements
    Route::delete('advertisements/destroy', 'AdvertisementsController@massDestroy')->name('advertisements.massDestroy');
    Route::post('advertisements/media', 'AdvertisementsController@storeMedia')->name('advertisements.storeMedia');
    Route::post('advertisements/ckmedia', 'AdvertisementsController@storeCKEditorImages')->name('advertisements.storeCKEditorImages');
    Route::resource('advertisements', 'AdvertisementsController');

    // Post Types
    Route::delete('post-types/destroy', 'PostTypesController@massDestroy')->name('post-types.massDestroy');
    Route::resource('post-types', 'PostTypesController');

    // Posts
    Route::delete('posts/destroy', 'PostsController@massDestroy')->name('posts.massDestroy');
    Route::resource('posts', 'PostsController');

    // Marital Statuses
    Route::delete('marital-statuses/destroy', 'MaritalStatusesController@massDestroy')->name('marital-statuses.massDestroy');
    Route::resource('marital-statuses', 'MaritalStatusesController');

    // Disability Types
    Route::delete('disability-types/destroy', 'DisabilityTypesController@massDestroy')->name('disability-types.massDestroy');
    Route::resource('disability-types', 'DisabilityTypesController');

    // Religions
    Route::delete('religions/destroy', 'ReligionsController@massDestroy')->name('religions.massDestroy');
    Route::resource('religions', 'ReligionsController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Castes
    Route::delete('castes/destroy', 'CastesController@massDestroy')->name('castes.massDestroy');
    Route::resource('castes', 'CastesController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::resource('countries', 'CountriesController');

    // Provinces
    Route::delete('provinces/destroy', 'ProvincesController@massDestroy')->name('provinces.massDestroy');
    Route::resource('provinces', 'ProvincesController');

    // Postal Codes
    Route::delete('postal-codes/destroy', 'PostalCodesController@massDestroy')->name('postal-codes.massDestroy');
    Route::resource('postal-codes', 'PostalCodesController');

    // Profiles
    Route::delete('profiles/destroy', 'ProfilesController@massDestroy')->name('profiles.massDestroy');
    Route::resource('profiles', 'ProfilesController');

    // Photos
    Route::delete('photos/destroy', 'PhotosController@massDestroy')->name('photos.massDestroy');
    Route::post('photos/media', 'PhotosController@storeMedia')->name('photos.storeMedia');
    Route::post('photos/ckmedia', 'PhotosController@storeCKEditorImages')->name('photos.storeCKEditorImages');
    Route::resource('photos', 'PhotosController');

    // Adresses
    Route::delete('adresses/destroy', 'AdressesController@massDestroy')->name('adresses.massDestroy');
    Route::resource('adresses', 'AdressesController');

    // Qualification Levels
    Route::delete('qualification-levels/destroy', 'QualificationLevelsController@massDestroy')->name('qualification-levels.massDestroy');
    Route::resource('qualification-levels', 'QualificationLevelsController');

    // Boards
    Route::delete('boards/destroy', 'BoardsController@massDestroy')->name('boards.massDestroy');
    Route::resource('boards', 'BoardsController');

    // Academic Qualifications
    Route::delete('academic-qualifications/destroy', 'AcademicQualificationsController@massDestroy')->name('academic-qualifications.massDestroy');
    Route::post('academic-qualifications/media', 'AcademicQualificationsController@storeMedia')->name('academic-qualifications.storeMedia');
    Route::post('academic-qualifications/ckmedia', 'AcademicQualificationsController@storeCKEditorImages')->name('academic-qualifications.storeCKEditorImages');
    Route::resource('academic-qualifications', 'AcademicQualificationsController');

    // Eligibility Tests
    Route::delete('eligibility-tests/destroy', 'EligibilityTestsController@massDestroy')->name('eligibility-tests.massDestroy');
    Route::resource('eligibility-tests', 'EligibilityTestsController');

    // Foreign Visits
    Route::delete('foreign-visits/destroy', 'ForeignVisitsController@massDestroy')->name('foreign-visits.massDestroy');
    Route::resource('foreign-visits', 'ForeignVisitsController');

    // Referees
    Route::delete('referees/destroy', 'RefereesController@massDestroy')->name('referees.massDestroy');
    Route::resource('referees', 'RefereesController');

    // Employment History
    Route::delete('employment-histories/destroy', 'EmploymentHistoryController@massDestroy')->name('employment-histories.massDestroy');
    Route::resource('employment-histories', 'EmploymentHistoryController');

    // Advertisement Types
    Route::delete('advertisement-types/destroy', 'AdvertisementTypesController@massDestroy')->name('advertisement-types.massDestroy');
    Route::resource('advertisement-types', 'AdvertisementTypesController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Faq Category
    Route::delete('faq-categories/destroy', 'FaqCategoryController@massDestroy')->name('faq-categories.massDestroy');
    Route::resource('faq-categories', 'FaqCategoryController');

    // Faq Question
    Route::delete('faq-questions/destroy', 'FaqQuestionController@massDestroy')->name('faq-questions.massDestroy');
    Route::resource('faq-questions', 'FaqQuestionController');

    // Advertisements
    Route::delete('advertisements/destroy', 'AdvertisementsController@massDestroy')->name('advertisements.massDestroy');
    Route::post('advertisements/media', 'AdvertisementsController@storeMedia')->name('advertisements.storeMedia');
    Route::post('advertisements/ckmedia', 'AdvertisementsController@storeCKEditorImages')->name('advertisements.storeCKEditorImages');
    Route::resource('advertisements', 'AdvertisementsController');

    // Post Types
    Route::delete('post-types/destroy', 'PostTypesController@massDestroy')->name('post-types.massDestroy');
    Route::resource('post-types', 'PostTypesController');

    // Posts
    Route::delete('posts/destroy', 'PostsController@massDestroy')->name('posts.massDestroy');
    Route::resource('posts', 'PostsController');

    // Marital Statuses
    Route::delete('marital-statuses/destroy', 'MaritalStatusesController@massDestroy')->name('marital-statuses.massDestroy');
    Route::resource('marital-statuses', 'MaritalStatusesController');

    // Disability Types
    Route::delete('disability-types/destroy', 'DisabilityTypesController@massDestroy')->name('disability-types.massDestroy');
    Route::resource('disability-types', 'DisabilityTypesController');

    // Religions
    Route::delete('religions/destroy', 'ReligionsController@massDestroy')->name('religions.massDestroy');
    Route::resource('religions', 'ReligionsController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Castes
    Route::delete('castes/destroy', 'CastesController@massDestroy')->name('castes.massDestroy');
    Route::resource('castes', 'CastesController');

    // Countries
    Route::delete('countries/destroy', 'CountriesController@massDestroy')->name('countries.massDestroy');
    Route::resource('countries', 'CountriesController');

    // Provinces
    Route::delete('provinces/destroy', 'ProvincesController@massDestroy')->name('provinces.massDestroy');
    Route::resource('provinces', 'ProvincesController');

    // Postal Codes
    Route::delete('postal-codes/destroy', 'PostalCodesController@massDestroy')->name('postal-codes.massDestroy');
    Route::resource('postal-codes', 'PostalCodesController');

    // Profiles
    Route::delete('profiles/destroy', 'ProfilesController@massDestroy')->name('profiles.massDestroy');
    Route::resource('profiles', 'ProfilesController');

    // Photos
    Route::delete('photos/destroy', 'PhotosController@massDestroy')->name('photos.massDestroy');
    Route::post('photos/media', 'PhotosController@storeMedia')->name('photos.storeMedia');
    Route::post('photos/ckmedia', 'PhotosController@storeCKEditorImages')->name('photos.storeCKEditorImages');
    Route::resource('photos', 'PhotosController');

    // Adresses
    Route::delete('adresses/destroy', 'AdressesController@massDestroy')->name('adresses.massDestroy');
    Route::resource('adresses', 'AdressesController');

    // Qualification Levels
    Route::delete('qualification-levels/destroy', 'QualificationLevelsController@massDestroy')->name('qualification-levels.massDestroy');
    Route::resource('qualification-levels', 'QualificationLevelsController');

    // Boards
    Route::delete('boards/destroy', 'BoardsController@massDestroy')->name('boards.massDestroy');
    Route::resource('boards', 'BoardsController');

    // Academic Qualifications
    Route::delete('academic-qualifications/destroy', 'AcademicQualificationsController@massDestroy')->name('academic-qualifications.massDestroy');
    Route::post('academic-qualifications/media', 'AcademicQualificationsController@storeMedia')->name('academic-qualifications.storeMedia');
    Route::post('academic-qualifications/ckmedia', 'AcademicQualificationsController@storeCKEditorImages')->name('academic-qualifications.storeCKEditorImages');
    Route::resource('academic-qualifications', 'AcademicQualificationsController');

    // Eligibility Tests
    Route::delete('eligibility-tests/destroy', 'EligibilityTestsController@massDestroy')->name('eligibility-tests.massDestroy');
    Route::resource('eligibility-tests', 'EligibilityTestsController');

    // Foreign Visits
    Route::delete('foreign-visits/destroy', 'ForeignVisitsController@massDestroy')->name('foreign-visits.massDestroy');
    Route::resource('foreign-visits', 'ForeignVisitsController');

    // Referees
    Route::delete('referees/destroy', 'RefereesController@massDestroy')->name('referees.massDestroy');
    Route::resource('referees', 'RefereesController');

    // Employment History
    Route::delete('employment-histories/destroy', 'EmploymentHistoryController@massDestroy')->name('employment-histories.massDestroy');
    Route::resource('employment-histories', 'EmploymentHistoryController');

    // Advertisement Types
    Route::delete('advertisement-types/destroy', 'AdvertisementTypesController@massDestroy')->name('advertisement-types.massDestroy');
    Route::resource('advertisement-types', 'AdvertisementTypesController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});
