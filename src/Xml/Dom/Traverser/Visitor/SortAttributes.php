<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use DOMAttr;
use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Manipulator\append;
use function VeeWee\Xml\Dom\Predicate\is_element;

final class SortAttributes extends AbstractVisitor
{
    public function onNodeEnter(DOMNode $node): Action
    {
        if (!is_element($node)) {
            return new Action\Noop();
        }

        attributes_list($node)
            ->sort(static fn (DOMAttr $a, DOMAttr $b): int => $a->nodeName <=> $b->nodeName)
            ->forEach(
                static function (DOMAttr $attr) use ($node): void {
                    append($attr)($node);
                }
            );

        return new Action\Noop();
    }
}
