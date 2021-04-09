<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Collection;

use Countable;
use DOMNode;
use DOMNodeList;
use IteratorAggregate;
use Psl\Type\TypeInterface;
use VeeWee\Xml\Dom\Xpath;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Iter\reduce;
use function Psl\Vec\filter;
use function Psl\Vec\flat_map;
use function Psl\Vec\map;

/**
 * @template T of DOMNode
 * @implements IteratorAggregate<T>
 */
final class NodeList implements Countable, IteratorAggregate
{
    /**
     * @var list<T>
     */
    private array $nodes;

    /**
     * @param list<T> $nodes
     */
    public function __construct(...$nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @template X of DOMNode
     * @param DOMNodeList<X> $list
     *
     * @return NodeList<X>
     */
    public static function fromDOMNodeList(DOMNodeList $list): self
    {
        return new self(...$list);
    }

    /**
     * @return \Generator<T>
     */
    public function getIterator()
    {
        yield from $this->nodes;
    }

    /**
     * @return T|null
     */
    public function item(int $index)
    {
        return $this->nodes[$index] ?? null;
    }

    public function count(): int
    {
        return count($this->nodes);
    }

    /**
     * @template R
     * @param callable(T): R $mapper
     *
     * @return list<R>
     */
    public function map(callable $mapper): array
    {
        return map($this->nodes, $mapper);
    }

    /**
     * @param callable(T): bool $predicate
     * @return NodeList<T>
     */
    public function filter(callable $predicate): self
    {
        return new self(...filter($this->nodes, $predicate));
    }

    /**
     * TODO : configurators?
     * TODO : DOMElement is not set correctly on the query locator
     *
     * @throws RuntimeException
     * @return NodeList<T>
     */
    public function query(string $xpath): self
    {
        return new self(
            ...flat_map(
                $this->nodes,
                /**
                 * @param T $node
                 * @return DOMNodeList<T>
                 */
                static fn (DOMNode $node): DOMNodeList
                    => Xpath::fromUnsafeNode($node)->query($xpath, $node)
            )
        );
    }

    /**
     * @template R
     * @param callable(R, T): R $reducer
     * @param R $initial
     * @return R
     */
    public function reduce(callable $reducer, mixed $initial): mixed
    {
        return reduce($this->nodes, $reducer, $initial);
    }

    /**
     * @template X
     * @param TypeInterface<X> $type
     * @return list<X>
     */
    public function evaluate(string $expression, TypeInterface $type): array
    {
        return $this->map(
            static fn (DOMNode $node): mixed => Xpath::fromUnsafeNode($node)->evaluate($expression, $type, $node)
        );
    }

    /**
     * @return T|null
     */
    public function first()
    {
        return $this->item(0);
    }

    /**
     * @return T|null
     */
    public function last()
    {
        return $this->item($this->count() - 1);
    }

    public function siblings(): self
    {
        return new self(
            ...flat_map(
                $this->nodes,
                static fn (DOMNode $node) => filter(
                    $node->parentNode->childNodes ?? [],
                    static fn (DOMNode $sibling): bool => $sibling !== $node
                )
            )
        );
    }

    public function ancestors(): self
    {
        return new self(
            ...flat_map(
                $this->nodes,
                static function (DOMNode $current) {
                    while ($current->parentNode !== null) {
                        yield $current->parentNode;
                        $current = $current->parentNode;
                    }
                }
            )
        );
    }

    public function children(): self
    {
        return new self(
            ...flat_map(
                $this->nodes,
                static fn (DOMNode $node): DOMNodeList => $node->childNodes
            )
        );
    }
}
