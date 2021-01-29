<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use Psl\Exception\InvariantViolationException;
use Webmozart\Assert\Assert;
use function Psl\Arr\at;
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
        $this->guardSequenceNotEmpty();
        $popped = $this->elementNodes;
        array_pop($popped);
        return new self(...$popped);
    }

    public function append(ElementNode $element): self
    {
        return new self(...[...$this->elementNodes, $element]);
    }

    public function current(): ElementNode
    {
        $this->guardSequenceNotEmpty();
        return last($this->elementNodes);
    }

    public function parent(): ?ElementNode
    {
        try {
            $elementCount = count($this->elementNodes);
            $element = at($this->elementNodes, $elementCount - 1);
        } catch (InvariantViolationException) {
            return null;
        }

        return $element;
    }

    /**
     * @return list<ElementNode>
     */
    public function sequence(): array
    {
        return $this->elementNodes;
    }

    private function guardSequenceNotEmpty(): void
    {
        Assert::minCount($this->elementNodes, 1, 'The node sequence is empty. Can not fetch current item!');
    }
}
