<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator;

use Closure;
use \DOM\Node;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @no-named-arguments
 * @throws RuntimeException
 * @return Closure(\DOM\Node): \DOM\Node
 */
function append(\DOM\Node ... $nodes): Closure
{
    return static fn (\DOM\Node $target): \DOM\Node => disallow_issues(
        static function () use ($target, $nodes) {
            foreach ($nodes as $node) {
                $target->appendChild($node);
            }

            return $target;
        }
    );
}
