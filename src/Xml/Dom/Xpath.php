<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use \DOM\Node;
use \DOM\XPath as DOMXPath;
use InvalidArgumentException;
use Psl\Type\TypeInterface;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Dom\Xpath\Locator\evaluate;
use function VeeWee\Xml\Dom\Xpath\Locator\query;
use function VeeWee\Xml\Dom\Xpath\Locator\query_single;
use function VeeWee\Xml\Internal\configure;

final class Xpath
{
    private function __construct(
        private DOMXPath $xpath
    ) {
    }

    /**
     * @param list<callable(DOMXPath): DOMXPath> $configurators
     */
    public static function fromDocument(Document $document, callable ... $configurators): self
    {
        return new self(
            configure(...$configurators)(new DOMXPath($document->toUnsafeDocument()))
        );
    }

    /**
     * @param list<callable(DOMXPath): DOMXPath> $configurators
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public static function fromUnsafeNode(\DOM\Node $node, callable ... $configurators): self
    {
        return self::fromDocument(
            Document::fromUnsafeDocument(
                detect_document($node)
            ),
            ...$configurators
        );
    }

    /**
     * @template T
     * @param callable(DOMXPath): T $locator
     *
     * @return T
     * @throws RuntimeException
     */
    public function locate(callable $locator)
    {
        return $locator($this->xpath);
    }

    /**
     * @throws RuntimeException
     * @return NodeList<\DOM\Node>
     */
    public function query(string $expression, \DOM\Node $contextNode = null): NodeList
    {
        return $this->locate(query($expression, $contextNode));
    }

    /**
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function querySingle(string $expression, \DOM\Node $contextNode = null): \DOM\Node
    {
        return $this->locate(query_single($expression, $contextNode));
    }

    /**
     * @template T
     *
     * @param TypeInterface<T> $type
     *
     * @return T
     * @throws RuntimeException
     */
    public function evaluate(string $expression, TypeInterface $type, \DOM\Node $contextNode = null)
    {
        return $this->locate(evaluate($expression, $type, $contextNode));
    }
}
