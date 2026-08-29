<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Global strings
|--------------------------------------------------------------------------
|
| Shared labels that are not owned by a single module. Module strings live in
| their own file -- lang/en/auth.php, and later scrutiny.php, payment.php --
| so a module can be read, reviewed and translated on its own.
|
| Nothing is added speculatively: a key exists when a view uses it, and
| tests/Feature/Foundation/TranslationTest fails the build if a referenced key
| resolves to itself.
|
| On the 5,702-key backlog recorded in M00 §7: the files were never missing,
| they were at the Laravel 8 path resources/lang/. That directory shadowed
| lang/ and both are now consolidated here. cruds.php was not carried across,
| because every one of the 260 views it labelled was deleted in Wave 0.
|
*/

return [

    'foundation_placeholder' => 'Wave 0 — foundation. No public routes are published yet.',
    'wave_one_placeholder' => 'Wave 1 — identity. Vacancies and applications arrive in a later wave.',

    'university' => 'Aligarh Muslim University',
    'recruitment_portal' => 'Recruitment Portal',
    'office_of_coe' => 'Office of the Controller of Examinations',

    'dashboard' => 'Dashboard',
    'admin_dashboard' => 'Administration',
    'skip_to_content' => 'Skip to content',

];
