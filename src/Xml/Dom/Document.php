<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use Closure;
use DOMDocument;
use DOMNode;
use DOMXPath;
use VeeWee\Xml\Dom\Traverser\Traverser;
use VeeWee\Xml\Dom\Traverser\Visitor;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Exception\RuntimeException;
use function VeeWee\Xml\Dom\Configurator\loader;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;
use function VeeWee\Xml\Dom\Loader\xml_node_loader;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;
use function VeeWee\Xml\Dom\Locator\document_element;
use function VeeWee\Xml\Dom\Mapper\xml_string;
use function VeeWee\Xml\Util\configure;

final class Document
{
    private function __construct(
        private DOMDocument $document
    ) {
    }

    public static function empty(): self
    {
        return new self(new DOMDocument());
    }

    /**
     * @param list<\Closure(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function configure(Closure ... $configurators): self
    {
        $document = configure(...$configurators)(new DOMDocument());

        return new self($document);
    }

    /**
     * @param list<\Closure(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlFile(string $file, Closure ...$configurators): self
    {
        return self::configure(
            loader(xml_file_loader($file)),
            ...$configurators
        );
    }

    /**
     * @param non-empty-string $xml
     * @param list<\Closure(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlString(string $xml, Closure ...$configurators): self
    {
        return self::configure(
            loader(xml_string_loader($xml)),
            ...$configurators
        );
    }

    /**
     * @param list<\Closure(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlNode(DOMNode $node, Closure ...$configurators): self
    {
        return self::configure(
            loader(xml_node_loader($node)),
            ...$configurators
        );
    }

    /**
     * @param list<\Closure(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromUnsafeDocument(DOMDocument $document, Closure ...$configurators): self
    {
        return new self(
            configure(...$configurators)($document)
        );
    }

    public function toUnsafeDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @template T
     * @param \Closure(DOMDocument): T $locator
     *
     * @return T
     */
    public function locate(Closure $locator)
    {
        return $locator($this->document);
    }

    /**
     * @param \Closure(DOMDocument): mixed $manipulator
     *
     * @return $this
     */
    public function manipulate(Closure $manipulator): self
    {
        $manipulator($this->document);

        return $this;
    }

    /**
     * @param list<\Closure(DOMDocument): (list<DOMNode>|DOMNode)> $builders
     *
     * @return list<DOMNode>
     */
    public function build(Closure ... $builders): array
    {
        return Builder\nodes(...$builders)($this->document);
    }

    /**
     * @param \Closure(DOMDocument): IssueCollection $validator
     */
    public function validate(Closure $validator): IssueCollection
    {
        return $validator($this->document);
    }

    /**
     * @param list<\Closure(DOMXPath): DOMXPath> $configurators
     */
    public function xpath(Closure ...$configurators): Xpath
    {
        return Xpath::fromDocument($this, ...$configurators);
    }

    /**
     * @template T
     * @param \Closure(DOMDocument): T $mapper
     *
     * @return T
     */
    public function map(Closure $mapper)
    {
        return $mapper($this->document);
    }

    /**
     * @no-named-arguments
     */
    public function traverse(Visitor ... $visitors): DOMNode
    {
        $traverser = new Traverser(...$visitors);
        return $traverser->traverse($this->map(document_element()));
    }

    public function toXmlString(): string
    {
        return $this->map(xml_string());
    }
}
