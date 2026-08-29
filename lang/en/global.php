<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Global strings
|--------------------------------------------------------------------------
|
| Shared labels and messages that are not owned by a single module. Module
| strings live in their own file -- lang/en/scrutiny.php, lang/en/payment.php
| -- so that a module can be read, reviewed and translated on its own.
|
| Nothing is added here speculatively. A key exists when a view uses it, and
| tests/Feature/Foundation/TranslationTest fails the build if a rendered key
| resolves to itself.
|
| Note on the 5,702-key backlog recorded in M00 §7: it was real, but it was a
| property of the 260 generated views, all of which this wave deleted. The
| backlog is therefore closed by deletion rather than by translation, and the
| test is what stops it accumulating again as views are rebuilt.
|
*/

return [

    'foundation_placeholder' => 'Wave 0 — foundation. No public routes are published yet.',

];
