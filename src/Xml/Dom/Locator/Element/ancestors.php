<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Locator\Element;

use \Dom\Element;
use \Dom\Node;
use Generator;
use VeeWee\Xml\Dom\Collection\NodeList;
use function VeeWee\Xml\Dom\Predicate\is_element;

/**
 * @return NodeList<\Dom\Element>
 */
function ancestors(\Dom\Node $node): NodeList
{
    return new NodeList(
        ...(
            /**
             * @return Generator<int, \Dom\Element>
             */
            static function (\Dom\Node $next) {
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
