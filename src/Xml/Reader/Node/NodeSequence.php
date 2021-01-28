<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use Webmozart\Assert\Assert;
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

    public function current(): ElementNode
    {
        $current = last($this->elementNodes);
        Assert::notNull($current, 'The node sequence is empty. Can not fetch current item!');

        return $current;
    }
}
