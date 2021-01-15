<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Builder;

use DOMElement;
use DOMNode;

/**
 * @param list<callable(DOMNode): DOMNode> $configurators
 *
 * @return DOMElement
 */
function children(callable ...$builders): callable {
    return static function (DOMNode $node) use ($builders): DOMNode {
        foreach ($builders as $builder) {
            $node->appendChild($builder());
        }

        return $node;
    };
}
