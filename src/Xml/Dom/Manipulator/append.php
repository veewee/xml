<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator;

use Closure;
use \Dom\Node;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Predicate\is_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @no-named-arguments
 * @throws RuntimeException
 * @return Closure(\Dom\Node): \Dom\Node
 */
function append(\Dom\Node ... $nodes): Closure
{
    return static fn (\Dom\Node $target): \Dom\Node => disallow_issues(
        static function () use ($target, $nodes) {
            foreach ($nodes as $node) {
                // Attributes cannot be appended with appendChild.
                // Setting the attribute node to the element is the correct way to append an attribute.
                if (is_attribute($node) && is_element($target)) {
                    $target->setAttributeNode($node);
                    continue;
                }

                $target->appendChild($node);
            }

            return $target;
        }
    );
}
