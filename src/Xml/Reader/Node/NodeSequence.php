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
        $this->guardSequenceNotEmpty();
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
        $this->guardSequenceNotEmpty();

        $elementCount = count($this->elementNodes);

        return $this->elementNodes[$elementCount-1];
    }

    public function parent(): ?ElementNode
    {
        $elementCount = count($this->elementNodes);
        return $this->elementNodes[$elementCount - 2] ?? null;
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
    private function guardSequenceNotEmpty(): void
    {
        Assert::minCount($this->elementNodes, 1, 'The node sequence is empty. Can not fetch current item!');
    }
}
