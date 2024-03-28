<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use \DOM\Element;
use \DOM\Node;
use Generator;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\DOM\Element>
 */
function ancestors(\DOM\Node $node): NodeList
{
    return new NodeList(
        ...(
            /**
             * @return Generator<int, \DOM\Element>
             */
            static function (\DOM\Node $next) {
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
