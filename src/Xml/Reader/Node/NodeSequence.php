<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use InvalidArgumentException;
use Psl\Exception\InvariantViolationException;
use Webmozart\Assert\Assert;
use function Psl\Arr\at;
use function Psl\Arr\last;

final class NodeSequence
{
    /**
     * @var list<ElementNode>
     */
    private array $elementNodes;

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

        /** @var ElementNode $result */
        $result = last($this->elementNodes);

        return $result;
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

    /**
     * @throws InvalidArgumentException
     */
    private function guardSequenceNotEmpty(): void
    {
        Assert::minCount($this->elementNodes, 1, 'The node sequence is empty. Can not fetch current item!');
    }
}
