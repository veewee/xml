<?php
declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser\Visitor;

use DOMNode;
use VeeWee\Xml\Dom\Traverser\Action;
use function VeeWee\Xml\Dom\Locator\Attribute\xmlns_attributes_list;
use function VeeWee\Xml\Dom\Predicate\is_element;

final class RemoveNamespaces extends AbstractVisitor
{
    public function onNodeLeave(DOMNode $node): Action
    {
        if (!is_element($node)) {
            return new Action\Noop();
        }

        $namespaces = xmlns_attributes_list($node);
        foreach ($namespaces as $namespace) {
            $node->removeAttributeNS(
                $namespace->namespaceURI,
                $namespace->prefix
            );
        }

        return new Action\Noop();
    }
}
