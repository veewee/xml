<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use \DOM\Element;
use \DOM\NameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @throws RuntimeException
 */
function remove_namespace(\DOM\NameSpaceNode $target, \DOM\Element $parent): \DOM\NameSpaceNode
{
    return disallow_issues(
        /**
         * @throws RuntimeException
         */
        static function () use ($target, $parent): \DOM\NameSpaceNode {
            disallow_libxml_false_returns(
                $parent->removeAttributeNS($target->namespaceURI, $target->prefix),
                'Could not remove xmlns attribute from dom element'
            );

            return $target;
        }
    );
}
