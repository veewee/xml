<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class NodeSequence
{
    /**
     * @var list<ElementNode>
     */
    private array $elementNodes;

    /**
     * @no-named-arguments
     */
    public function __construct(ElementNode ... $elementNodes)
    {
        $this->elementNodes = $elementNodes;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function pop(): self
    {
        $this->calculateNonEmptyElementsCount();
        $popped = $this->elementNodes;
        array_pop($popped);
        return new self(...$popped);
    }

    public function append(ElementNode $element): self
    {
        return new self(...[...$this->elementNodes, $element]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function current(): ElementNode
    {
        return $this->elementNodes[$this->calculateNonEmptyElementsCount() -1];
    }

    public function parent(): ?ElementNode
    {
        $count = count($this->elementNodes);
        if ($count <= 1) {
            return null;
        }

        return $this->elementNodes[$count - 2];
    }

    /**
     * @return list<ElementNode>
     */
    public function sequence(): array
    {
        return $this->elementNodes;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function calculateNonEmptyElementsCount(): int
    {
        $count = count($this->elementNodes);
        Assert::true($count > 0, 'The node sequence is empty. Can not fetch current item!');

        return $count;
    }
}
