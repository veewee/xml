<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use Closure;
use DOMDocument;
use Dom\Element;
use Dom\Node;
use Dom\XMLDocument;
use Dom\XPath as DOMXPath;
use VeeWee\Xml\Dom\Traverser\Traverser;
use VeeWee\Xml\Dom\Traverser\Visitor;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Vec\map;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\to_unsafe_legacy_document;
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
    public static function fromXmlNode(Node $node, callable ...$configurators): self
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
     * Converts this document into a legacy DOMDocument via an XML round-trip.
     *
     * The documentURI is preserved. Note that line numbers may differ from the original
     * because the new DOM's saveXML() can reformat the output (e.g., collapsing
     * multi-line opening tags into single lines).
     */
    public function toUnsafeLegacyDocument(): DOMDocument
    {
        return $this->map(to_unsafe_legacy_document());
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

    public function locateDocumentElement(): Element
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
     * @psalm-suppress ArgumentTypeCoercion - nodes() works on node but we provide the parent type XMLDocument.
     *
     * @param list<callable(XMLDocument): (list<Node>|Node)> $builders
     *
     * @return list<Node>
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
    public function traverse(Visitor ... $visitors): Node
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
    public function stringifyNode(Node $node): string
    {
        return xml_string()($node);
    }
}
