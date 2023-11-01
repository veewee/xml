<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use Countable;
use Generator;
use InvalidArgumentException;
use Webmozart\Assert\Assert;
use function Psl\Vec\slice;

final class NodeSequence implements Countable
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

    public function count(): int
    {
        return \count($this->elementNodes);
    }

    /**
     * @param non-negative-int $start
     * @param non-negative-int|null $length
     */
    public function slice(int $start, ?int $length = null): self
    {
        return new self(...slice($this->elementNodes, $start, $length));
    }

    /**
     * Replays every step in the sequence
     *
     * @return Generator<non-negative-int, NodeSequence, mixed, void>
     */
    public function replay(): Generator
    {
        $step = new self();
        foreach ($this->elementNodes as $index => $node) {
            $step = $step->append($node);
            yield $index => $step;
        }
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
