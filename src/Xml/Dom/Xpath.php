<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use Closure;
use DOMNode;
use DOMXPath;
use InvalidArgumentException;
use Psl\Type\TypeInterface;
use VeeWee\Xml\Dom\Collection\NodeList;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Locator\Node\detect_document;
use function VeeWee\Xml\Dom\Xpath\Locator\evaluate;
use function VeeWee\Xml\Dom\Xpath\Locator\query;
use function VeeWee\Xml\Dom\Xpath\Locator\query_single;
use function VeeWee\Xml\Util\configure;

final class Xpath
{
    private function __construct(
        private DOMXPath $xpath
    ) {
    }

    /**
     * @param list<\Closure(DOMXPath): DOMXPath> $configurators
     */
    public static function fromDocument(Document $document, Closure ... $configurators): self
    {
        return new self(
            configure(...$configurators)(new DOMXPath($document->toUnsafeDocument()))
        );
    }

    /**
     * @param list<\Closure(DOMXPath): DOMXPath> $configurators
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public static function fromUnsafeNode(DOMNode $node, Closure ... $configurators): self
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
     * @param \Closure(DOMXpath): T $locator
     *
     * @return T
     * @throws RuntimeException
     */
    public function locate(Closure $locator)
    {
        return $locator($this->xpath);
    }

    /**
     * @template T of DOMNode
     * @throws RuntimeException
     * @return NodeList<T>
     */
    public function query(string $expression, DOMNode $contextNode = null): NodeList
    {
        return $this->locate(query($expression, $contextNode));
    }

    /**
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function querySingle(string $expression, DOMNode $contextNode = null): DOMNode
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
    public function evaluate(string $expression, TypeInterface $type, DOMNode $contextNode = null)
    {
        return $this->locate(evaluate($expression, $type, $contextNode));
    }
}
