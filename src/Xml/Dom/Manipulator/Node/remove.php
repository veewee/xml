<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Predicate\is_attribute;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @throws RuntimeException
 */
function remove(DOMNode $target): DOMNode
{
    return disallow_issues(
        /**
         * @throws RuntimeException
         */
        static function () use ($target): DOMNode {
            $parent = $target->parentNode;
            if (!$parent || !is_element($parent)) {
                throw RuntimeException::withMessage('Can not remove a node without parent');
            }

            if (is_attribute($target)) {
                disallow_libxml_false_returns(
                    $parent->removeAttributeNode($target),
                    'Could not remove attribute from dom element'
                );

                return $target;
            }

            return $parent->removeChild($target);
        }
    );
}
