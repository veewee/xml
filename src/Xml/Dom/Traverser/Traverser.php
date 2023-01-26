<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Traverser;

use DOMNode;
use function VeeWee\Xml\Dom\Locator\Attribute\attributes_list;
use function VeeWee\Xml\Dom\Locator\Node\children;

final class Traverser
{
    /**
     * @var list<Visitor>
     */
    private array $visitors;

    /**
     * @no-named-arguments
     */
    public function __construct(Visitor ... $visitors)
    {
        $this->visitors = $visitors;
    }

    public function traverse(DOMNode $node): DOMNode
    {
        $this->enterNode($node);

        foreach (attributes_list($node) as $attribute) {
            $this->traverse($attribute);
        }

        foreach (children($node) as $child) {
            $this->traverse($child);
        }

        $this->leaveNode($node);

        return $node;
    }

    private function enterNode(DOMNode $node): void
    {
        foreach ($this->visitors as $visitor) {
            $visitor->onNodeEnter($node)($node);
        }
    }

    private function leaveNode(DOMNode $node): void
    {
        foreach ($this->visitors as $visitor) {
            $visitor->onNodeLeave($node)($node);
        }
    }
}
