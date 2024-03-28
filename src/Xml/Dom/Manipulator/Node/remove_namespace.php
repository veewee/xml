<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @throws RuntimeException
 */
function remove_namespace(\DOM\Attr $target, \DOM\Element $parent): \DOM\Attr
{
    return disallow_issues(
        /**
         * @throws RuntimeException
         */
        static function () use ($target, $parent): \DOM\Attr {
            disallow_libxml_false_returns(
                $parent->removeAttributeNode($target),
                'Could not remove xmlns attribute from dom element'
            );

            return $target;
        }
    );
}
