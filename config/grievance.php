<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Grievance
|--------------------------------------------------------------------------
|
| Nothing in the UGC Regulations or the CRR requires a grievance regime, and
| the only finality clauses run the other way. This is University policy under
| the Executive Council, and the service level is theirs to set.
|
*/

return [

    'sla_days' => (int) env('GRIEVANCE_SLA_DAYS', 15),

];
