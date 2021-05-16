<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use DOMElement;
use DOMNode;
use Generator;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<DOMElement>
 */
function ancestors(DOMNode $node): NodeList
{
    return new NodeList(
        ...(
            /**
             * @return Generator<int, DOMElement>
             */
            static function (DOMNode $next) {
                while (($parent = $next->parentNode) !== null) {
                    if (is_element($parent)) {
                        yield $parent;
                    }
                    $next = $parent;
                }
            }
        )($node)
    );
}
