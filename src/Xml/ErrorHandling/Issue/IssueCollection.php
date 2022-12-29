<?php

declare(strict_types=1);

namespace VeeWee\Xml\ErrorHandling\Issue;

use Countable;
use IteratorAggregate;
use Psl\Dict;
use Psl\Iter\Iterator;
use Psl\Math;
use Psl\Str;
use Psl\Vec;

/**
 * @template-implements IteratorAggregate<int, Issue>
 */
final class IssueCollection implements Countable, IteratorAggregate
{
    /**
     * @var list<Issue>
     */
    private array $issues;

    /**
     * @no-named-arguments
     */
    public function __construct(Issue ...$errors)
    {
        $this->issues = $errors;
    }

    /**
     * @psalm-suppress LessSpecificImplementedReturnType
     * @return Iterator<int<0,max>, Issue>
     */
    public function getIterator(): Iterator
    {
        return Iterator::create($this->issues);
    }

    public function count(): int
    {
        return count($this->issues);
    }

    /**
     * @param (callable(Issue): bool) $filter
     */
    public function filter(callable $filter): self
    {
        return new self(...Dict\filter($this->issues, $filter(...)));
    }

    public function getHighestLevel(): ?Level
    {
        $issue = Math\max_by($this->issues, static fn (Issue $issue): int => $issue->level()->value());

        return $issue ? $issue->level() : null;
    }

    public function toString(): string
    {
        $values = Vec\values(Dict\map($this->issues, static fn (Issue $error): string => $error->toString()));

        return Str\join($values, PHP_EOL);
    }
}
