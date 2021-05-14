<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Manipulator\Node;

use DOMElement;
use DOMNameSpaceNode;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\ErrorHandling\disallow_issues;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @throws RuntimeException
 */
function remove_namespace(DOMNameSpaceNode $target, DOMElement $parent): DOMNameSpaceNode
{
    return disallow_issues(
        /**
         * @throws RuntimeException
         */
        static function () use ($target, $parent): DOMNameSpaceNode {
            disallow_libxml_false_returns(
                $parent->removeAttributeNS($target->namespaceURI, $target->prefix),
                'Could not remove xmlns attribute from dom element'
            );

            return $target;
        }
    );
}
