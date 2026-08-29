<?php

declare(strict_types=1);

namespace App\Domain\Communication;

use App\Models\MessageTemplate;
use RuntimeException;

/**
 * Substitutes the declared placeholders, and refuses an undeclared one.
 *
 * A template that references a field the segment cannot supply would otherwise
 * be discovered by 78,232 people receiving "Dear :name". The placeholders are
 * declared on the template so the mistake is caught before the campaign runs.
 */
final class RenderTemplate
{
    /**
     * @param  array<string, string|null>  $values
     */
    public function render(MessageTemplate $template, array $values): string
    {
        $declared = (array) ($template->placeholders ?? []);

        preg_match_all('/:([a-z_]+)/', $template->body, $matches);

        $used = array_unique($matches[1]);
        $undeclared = array_diff($used, $declared);

        if ($undeclared !== []) {
            throw new RuntimeException(sprintf(
                'The template uses undeclared placeholders: %s.',
                implode(', ', $undeclared),
            ));
        }

        $missing = array_diff($declared, array_keys(array_filter($values, static fn (?string $v): bool => $v !== null)));

        if ($missing !== []) {
            throw new RuntimeException(sprintf(
                'No value was supplied for: %s.',
                implode(', ', $missing),
            ));
        }

        $body = $template->body;

        foreach ($values as $key => $value) {
            $body = str_replace(':'.$key, (string) $value, $body);
        }

        return $body;
    }
}
