<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use VeeWee\Xml\Dom\Traverser\Action;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Predicate\is_element;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

final class SortAttributes extends AbstractVisitor
{
    public function onNodeEnter(\DOM\Node $node): Action
    {
        if (!is_element($node)) {
            return new Action\Noop();
        }

        attributes_list($node)
            ->sort(static fn (\DOM\Attr $a, \DOM\Attr $b): int => $a->nodeName <=> $b->nodeName)
            ->forEach(
                static function (\DOM\Attr $attr) use ($node): void {
                    disallow_issues(static function () use ($node, $attr) {
                        $node->removeAttributeNode($attr);
                        $node->setAttributeNode($attr);
                    });
                }
            );

        return new Action\Noop();
    }
}
