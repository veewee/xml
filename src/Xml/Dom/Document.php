<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use DOMDocument;
use DOMNode;
use DOMXPath;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\pipe;
use function VeeWee\Xml\Dom\Configurator\loader;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;
use function VeeWee\Xml\Dom\Loader\xml_node_loader;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;
use function VeeWee\Xml\Dom\Mapper\xml_string;

final class Document
{
    private function __construct(
        private DOMDocument $document
    ) {}

    public static function empty(): self
    {
        return new self(new DOMDocument());
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function configure(callable ... $configurators): self
    {
        $document = pipe(...$configurators)(new DOMDocument());

        return new self($document);
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlFile(string $file, callable ...$configurators): self
    {
        return self::configure(
            loader(xml_file_loader($file)),
            ...$configurators
        );
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlString(string $xml, callable ...$configurators): self
    {
        return self::configure(
            loader(xml_string_loader($xml)),
            ...$configurators
        );
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromXmlNode(DOMNode $node, callable ...$configurators): self
    {
        return self::configure(
            loader(xml_node_loader($node)),
            ...$configurators
        );
    }

    /**
     * @param list<callable(DOMDocument): DOMDocument> $configurators
     *
     * @throws RuntimeException
     */
    public static function fromUnsafeDocument(DOMDocument $document, callable ...$configurators): self
    {
        return new self(
            pipe(...$configurators)($document)
        );
    }

    public function toUnsafeDocument(): DOMDocument
    {
        return $this->document;
    }

    /**
     * @template T
     * @param callable(DOMDocument): T $locator
     *
     * @return T
     */
    public function locate(callable $locator)
    {
        return $locator($this->document);
    }

    /**
     * @param callable(DOMDocument): mixed $manipulator
     *
     * @return $this
     */
    public function manipulate(callable $manipulator): self
    {
        $manipulator($this->document);

        return $this;
    }

    /**
     * @param list<callable(DOMDocument): (list<DOMNode>|DOMNode)> $builders
     *
     * @return list<DOMNode>
     */
    public function build(callable ... $builders): array
    {
        return Builder\nodes(...$builders)($this->document);
    }

    /**
     * @param callable(DOMDocument): IssueCollection $validator
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
     * @param callable(DOMDocument): T $mapper
     *
     * @return T
     */
    public function map(callable $mapper)
    {
        return $mapper($this->document);
    }

    public function toXmlString(): string
    {
        return $this->map(xml_string());
    }
}
