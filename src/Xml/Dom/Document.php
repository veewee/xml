<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use Closure;
use \DOM\XMLDocument;
use \DOM\Element;
use \DOM\Node;
use \DOM\XPath as DOMXPath;
use VeeWee\Xml\Dom\Traverser\Traverser;
use VeeWee\Xml\Dom\Traverser\Visitor;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Vec\map;
use function VeeWee\Xml\Dom\Loader;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;
use function VeeWee\Xml\Internal\configure;

final class Document
{
    private function __construct(
        private XMLDocument $document
    ) {
    }

    public static function empty(): self
    {
        return new self(XMLDocument::createEmpty());
    }

    /**
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function configure(callable ... $configurators): self
    {
        $document = configure(...$configurators)(XMLDocument::createEmpty());

        return new self($document);
    }

    /**
     * @param callable(): XMLDocument $loader
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromLoader(callable $loader, callable ...$configurators): self
    {
        return new self(
            configure(...$configurators)($loader())
        );
    }

    /**
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlFile(string $file, callable ...$configurators): self
    {
        return self::fromLoader(Loader\xml_file_loader($file), ...$configurators);
    }

    /**
     * @param non-empty-string $xml
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlString(string $xml, callable ...$configurators): self
    {
        return self::fromLoader(Loader\xml_string_loader($xml), ...$configurators);
    }

    /**
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlNode(\DOM\Node $node, callable ...$configurators): self
    {
        return self::fromLoader(Loader\xml_node_loader($node), ...$configurators);
    }

    /**
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromUnsafeDocument(XMLDocument $document, callable ...$configurators): self
    {
        return self::fromLoader(static fn () => $document, ...$configurators);
    }

    public function toUnsafeDocument(): XMLDocument
    {
        return $this->document;
    }

    /**
     * @template T
     * @param callable(XMLDocument): T $locator
     *
     * @return T
     */
    public function locate(callable $locator)
    {
        return $locator($this->document);
    }

    public function locateDocumentElement(): \DOM\Element
    {
        return $this->locate(Locator\document_element());
    }

    /**
     * @param callable(XMLDocument): mixed $manipulator
     *
     * @return $this
     */
    public function manipulate(callable $manipulator): self
    {
        $manipulator($this->document);

        return $this;
    }

    /**
     * @param list<callable(XMLDocument): (list<\DOM\Node>|\DOM\Node)> $builders
     *
     * @return list<\DOM\Node>
     */
    public function build(callable ... $builders): array
    {
        return Builder\nodes(...map(
            $builders,
            static fn (callable $builder): Closure => $builder(...)
        ))($this->document);
    }

    /**
     * @param callable(XMLDocument): IssueCollection $validator
     */
    public function validate(callable $validator): IssueCollection
    {
        return $validator($this->document);
    }

    /**
     * @param list<callable(DOMXPath): DOMXPath> $configurators
     */
    public function xpath(callable ...$configurators): Xpath
    {
        return Xpath::fromDocument($this, ...$configurators);
    }

    /**
     * @template T
     * @param callable(XMLDocument): T $mapper
     *
     * @return T
     */
    public function map(callable $mapper)
    {
        return $mapper($this->document);
    }

    /**
     * @param list<callable(XMLDocument): XMLDocument> $configurators
     *
     * @throws RuntimeException
     */
    public function reconfigure(callable ... $configurators): self
    {
        return self::fromUnsafeDocument($this->document, ...$configurators);
    }

    /**
     * @no-named-arguments
     */
    public function traverse(Visitor ... $visitors): \DOM\Node
    {
        $traverser = new Traverser(...$visitors);
        return $traverser->traverse($this->map(document_element()));
    }

    /**
     * @return non-empty-string
     */
    public function toXmlString(): string
    {
        return $this->map(xml_string());
    }

    /**
     * @return non-empty-string
     */
    public function stringifyDocumentElement(): string
    {
        return xml_string()($this->locateDocumentElement());
    }

    /**
     * @return non-empty-string
     */
    public function stringifyNode(\DOM\Node $node): string
    {
        return xml_string()($node);
    }
}
