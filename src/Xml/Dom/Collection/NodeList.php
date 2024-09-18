<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Collection;

use Countable;
use Dom\Element;
use Dom\HTMLCollection;
use Dom\Node;
use Dom\NodeList as DOMNodeList;
use Dom\XPath as DOMXPath;
use Generator;
use InvalidArgumentException;
use IteratorAggregate;
use Psl\Type\TypeInterface;
use Traversable;
use VeeWee\Xml\Dom\Xpath;
use VeeWee\Xml\Exception\RuntimeException;
use Webmozart\Assert\Assert;
use function Psl\Iter\reduce;
use function Psl\Str\format;
use function Psl\Vec\filter;
use function Psl\Vec\flat_map;
use function Psl\Vec\map;
use function Psl\Vec\sort;
use function Psl\Vec\values;
use function VeeWee\Xml\Dom\Locator\Element\ancestors;
use function VeeWee\Xml\Dom\Locator\Element\children;
use function VeeWee\Xml\Dom\Locator\Element\siblings;

/**
 * @template T of Node
 * @implements IteratorAggregate<int, T>
 */
final class NodeList implements Countable, IteratorAggregate
{
    /**
     * @var list<T>
     */
    private array $nodes;

    /**
     * @no-named-arguments
     * @param list<T> $nodes
     */
    public function __construct(...$nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @template X of Node
     * @return self<X>
     *
     * @psalm-suppress InvalidReturnType, InvalidReturnStatement - It is empty alright!
     */
    public static function empty(): self
    {
        return new self();
    }

    /**
     * @return NodeList<Element>
     */
    public static function fromDOMHTMLCollection(HTMLCollection $list): self
    {
        return new self(...values($list->getIterator()));
    }

    /**
     * @template X of Node
     * @param DOMNodeList<X> $list
     * @return NodeList<X>
     */
    public static function fromDOMNodeList(DOMNodeList $list): self
    {
        return new self(...values($list->getIterator()));
    }

    /**
     * @template X of Node
     * @param class-string<X> $type
     * @return NodeList<X>
     * @throws InvalidArgumentException
     */
    public static function typed(string $type, iterable $nodes): self
    {
        Assert::allIsInstanceOf($nodes, $type);

        return new self(...values($nodes));
    }

    /**
     * @return Generator<int, T>
     */
    public function getIterator(): Traversable
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

    /**
     * @throws InvalidArgumentException
     * @return T
     */
    public function expectItem(int $index, string $message = '')
    {
        Assert::notNull($item = $this->item($index), format($message ?: 'No item found at index %s', $index));

        return $item;
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
        return map($this->nodes, $mapper(...));
    }

    /**
     * @param callable(T): void $mapper
     */
    public function forEach(callable $mapper): void
    {
        foreach ($this->nodes as $node) {
            $mapper($node);
        }
    }

    /**
     * @template X of Node
     * @param callable(T): iterable<X> $mapper
     *
     * @return NodeList<X>
     */
    public function detect(callable $mapper): self
    {
        return new self(
            ...flat_map(
                $this->nodes,
                $mapper(...)
            )
        );
    }

    /**
     * @param callable(T): bool $predicate
     * @return NodeList<T>
     */
    public function filter(callable $predicate): self
    {
        return new self(...filter($this->nodes, $predicate(...)));
    }

    /**
     * @return NodeList<T>
     */
    public function eq(int $index): self
    {
        if (!array_key_exists($index, $this->nodes)) {
            return self::empty();
        }

        return new self($this->nodes[$index]);
    }

    /**
     * @template R
     * @param callable(R, T): R $reducer
     * @param R $initial
     * @return R
     */
    public function reduce(callable $reducer, mixed $initial): mixed
    {
        return reduce($this->nodes, $reducer(...), $initial);
    }

    /**
     * @param list<callable(DOMXPath): DOMXPath> $configurators
     * @return NodeList<Node>
     *@throws RuntimeException
     */
    public function query(string $xpath, callable ... $configurators): self
    {
        return $this->detect(
            /**
             * @param T $node
             * @return NodeList<Node>
             */
            static fn (Node $node): NodeList
                => Xpath::fromUnsafeNode($node, ...$configurators)->query($xpath, $node)
        );
    }

    /**
     * @template X
     * @param list<callable(DOMXPath): DOMXPath> $configurators
     * @param TypeInterface<X> $type
     * @return list<X>
     */
    public function evaluate(string $expression, TypeInterface $type, callable ... $configurators): array
    {
        return $this->map(
            static fn (Node $node): mixed
                => Xpath::fromUnsafeNode($node, ...$configurators)->evaluate($expression, $type, $node)
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
     * @throws InvalidArgumentException
     * @return T
     */
    public function expectFirst(string $message = '')
    {
        return $this->expectItem(0, $message);
    }

    /**
     * @throws InvalidArgumentException
     * @return T
     */
    public function expectSingle(string $message = '')
    {
        Assert::true(
            $this->count() === 1,
            format($message ?: 'Expected to find exactly one node. Got %s', count($this))
        );

        return $this->expectItem(0);
    }

    /**
     * @return T|null
     */
    public function last()
    {
        return $this->item($this->count() - 1);
    }

    /**
     * @throws InvalidArgumentException
     * @return T
     */
    public function expectLast(string $message = '')
    {
        return $this->expectItem($this->count() - 1, $message);
    }

    /**
     * @return NodeList<Element>
     */
    public function siblings(): self
    {
        return $this->detect(
            /**
             * @return iterable<Element>
             */
            static fn (Node $node): NodeList => siblings($node)
        );
    }

    /**
     * @return NodeList<Element>
     */
    public function ancestors(): self
    {
        return $this->detect(
            /**
             * @return iterable<Element>
             */
            static fn (Node $node): NodeList => ancestors($node)
        );
    }

    /**
     * @return NodeList<Element>
     */
    public function children(): self
    {
        return $this->detect(
            /**
             * @return iterable<Element>
             */
            static fn (Node $node): NodeList => children($node)
        );
    }

    /**
     * @template X of Node
     * @param class-string<X> $type
     * @return NodeList<X>
     * @throws InvalidArgumentException
     */
    public function expectAllOfType(string $type): self
    {
        return self::typed($type, $this);
    }

    /**
     * @param callable(T, T): int $sorter
     *
     * @return NodeList<T>
     */
    public function sort(callable $sorter): self
    {
        return new self(...sort($this->nodes, $sorter(...)));
    }
}
