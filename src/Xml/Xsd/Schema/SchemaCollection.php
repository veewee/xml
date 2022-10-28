<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xsd\Schema;

use Closure;
use Countable;
use IteratorAggregate;
use Traversable;
use function Psl\Vec\filter;
use function Psl\Vec\map;

/**
 *
 * @psalm-immutable
 * @template-implements IteratorAggregate<int, Schema>
 */
final class SchemaCollection implements Countable, IteratorAggregate
{
    /**
     * @var list<Schema>
     */
    private array $schemas;

    /**
     * @no-named-arguments
     */
    public function __construct(Schema ... $schemas)
    {
        $this->schemas = $schemas;
    }

    public function getIterator(): Traversable
    {
        yield from $this->schemas;
    }

    public function count(): int
    {
        return count($this->schemas);
    }

    public function add(Schema $schema): self
    {
        return new self(...[...$this->schemas, $schema]);
    }

    /**
     * @param \Closure(SchemaCollection): SchemaCollection $manipulator
     */
    public function manipulate(Closure $manipulator): self
    {
        /** @psalm-suppress ImpureFunctionCall */
        return $manipulator($this);
    }

    /**
     * @param \Closure(Schema): bool $filter
     */
    public function filter(Closure $filter): self
    {
        /** @psalm-suppress ImpureFunctionCall */
        return new self(...filter($this->schemas, $filter));
    }

    /**
     * @template T
     * @param \Closure(Schema): T $mapper
     *
     * @return list<T>
     */
    public function map(Closure $mapper)
    {
        /** @psalm-suppress ImpureFunctionCall */
        return map($this->schemas, $mapper);
    }
}
