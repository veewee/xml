<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Matcher;

use Closure;
use VeeWee\Xml\Reader\Node\NodeSequence;
use function array_shift;

/**
 * A nested matcher can be used to match parts of an XML path in order to detect node you are interested in.
 * Every match will create some kind of breakpoint of which the next matcher will start.
 * This makes it possible to combine it with sequences.
 * Between every matcher, there might be multiple nodes in between.
 *
 * ```php
 * nested(
 *     document_element(),
 *     sequence(element_name('users'), element_name('user')),
 *     element_name('email')
 * );
 * ```
 *
 * @param non-empty-list<callable(NodeSequence): bool> $matchers
 *
 * @return Closure(NodeSequence): bool
 */
function nested(callable ... $matchers): Closure
{
    return static function (NodeSequence $sequence) use ($matchers) : bool {
        $lastMatchedAtIndex = -1;
        $currentMatcher = array_shift($matchers);
        if ($currentMatcher === null) {
            return false;
        }

        $stepCount = $sequence->count();
        foreach ($sequence->replay() as $index => $step) {
            // Slice the step NodeSequence based on previous "match" breakpoint
            // and see if it matches on current matcher:
            $step = $step->slice($lastMatchedAtIndex + 1);
            if (!$currentMatcher($step)) {
                continue;
            }

            // If there was a match, select the next matcher and store the last matched NodeSequence index.
            $currentMatcher = array_shift($matchers);
            $lastMatchedAtIndex = $index;

            // If the list of matchers is empty
            // The function will return true if the element is the last step in the complete sequence.
            // Otherwise, the nested match has an even deeper element on which we don't wish to match.
            if ($currentMatcher === null) {
                $isLastStep = $index === $stepCount - 1;

                return $isLastStep;
            }
        }

        return false;
    };
}
