<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use DOMAttr;
use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use function VeeWee\Xml\Dom\Locator\Attributes\attributes_list;
use function VeeWee\Xml\Dom\Manipulator\append;
use function VeeWee\Xml\Dom\Predicate\is_element;

final class SortAttributes extends AbstractVisitor
{
    public function onNodeEnter(DOMNode $node): Action
    {
        if (!is_element($node)) {
            return new Action\Noop();
        }

        $attributes = attributes_list($node)->sort(
            static fn (DOMAttr $a, DOMAttr $b): int => $a->nodeName <=> $b->nodeName
        );

        foreach ($attributes as $attribute) {
            // Appending is sufficient - that will overwrite the existing one and place it at then end of the node
            append($attribute)($node);
        }

        return new Action\Noop();
    }
}
