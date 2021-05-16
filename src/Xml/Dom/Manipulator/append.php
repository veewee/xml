<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator;

use DOMNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @no-named-arguments
 * @throws RuntimeException
 * @return callable(DOMNode): DOMNode
 */
function append(DOMNode ... $nodes): callable
{
    return static fn (DOMNode $target): DOMNode => disallow_issues(
        static function () use ($target, $nodes) {
            foreach ($nodes as $node) {
                $target->appendChild($node);
            }

            return $target;
        }
    );
}
