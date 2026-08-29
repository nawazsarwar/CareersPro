<?php

declare(strict_types=1);

namespace App\Domain\Merit;

use LogicException;

/**
 * Not an ordinary error.
 *
 * A screening score entering a teaching merit list is a breach of UGC 2018
 * cl. 4.1 I Note and cl. 5.3, and an appointment made on such a list can be
 * set aside. It extends LogicException because it means the caller has asked
 * for something the regulations forbid -- a bug in the program, not a bad
 * input from a user.
 */
final class StatutoryViolation extends LogicException {}
