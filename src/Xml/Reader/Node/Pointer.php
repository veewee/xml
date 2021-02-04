<?php

declare(strict_types=1);

namespace VeeWee\Xml\Reader\Node;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class Pointer
{
    /**
     * @var array<int, int>
     */
    private array $siblingsPerDepth;

    private int $depth;

    private NodeSequence $nodeSequence;

    private function __construct()
    {
        $this->nodeSequence = new NodeSequence();
        $this->depth = 0;
        $this->siblingsPerDepth = [];
    }

    public static function create(): self
    {
        return new self();
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function getNextSiblingPosition(): int
    {
        return ($this->siblingsPerDepth[$this->depth] ?? 0) + 1;
    }

    public function getNodeSequence(): NodeSequence
    {
        return $this->nodeSequence;
    }

    public function enterElement(ElementNode $element): void
    {
        $depth = $this->depth;
        $this->siblingsPerDepth[$depth] = isset($this->siblingsPerDepth[$depth]) ? ($this->siblingsPerDepth[$depth]+1) : 1;
        $this->depth++;
        $this->nodeSequence = $this->nodeSequence->append($element);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function leaveElement(): void
    {
        Assert::greaterThan($this->depth, 0, 'Currently at root level. Can not leave element!');

        unset($this->siblingsPerDepth[$this->depth]);
        $this->depth--;
        $this->nodeSequence = $this->nodeSequence->pop();
    }
}
