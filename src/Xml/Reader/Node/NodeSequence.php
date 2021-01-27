<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use function Psl\Arr\last;

final class NodeSequence
{
    /**
     * @var ElementNode[]
     */
    private array $elementNodes;

    public function __construct(ElementNode ... $elementNodes)
    {
        $this->elementNodes = $elementNodes;
    }

    public function pop(): self
    {
        $popped = $this->elementNodes;
        array_pop($popped);
        return new self(...$popped);
    }

    public function append(ElementNode $element): self
    {
        return new self(...[...$this->elementNodes, $element]);
    }

    public function current(): ?ElementNode
    {
        return last($this->elementNodes);
    }

    public function matches(string $xpath): bool
    {
        // TOdo ... for real !
        return $this->elementNodes[count($this->elementNodes) - 1]->localName === $xpath;
    }
}