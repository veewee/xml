<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom;

use DOMDocument;
use DOMNode;
use DOMXPath;
use VeeWee\Xml\ErrorHandling\Issue\IssueCollection;
use VeeWee\Xml\Exception\RuntimeException;
use function Psl\Fun\pipe;
use function Psl\Iter\reduce;
use function Psl\Type\is_array;
use function VeeWee\Xml\Dom\Configurator\loader;
use function VeeWee\Xml\Dom\Loader\xml_file_loader;
use function VeeWee\Xml\Dom\Loader\xml_node_loader;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

final class Document
{
    private DOMDocument $document;

    private function __construct(DOMDocument $document)
    {
        $this->document = $document;
    }

    /**
     * @throws RuntimeException
     */
    public static function configure(callable ... $configurators): self
    {
        return new self(
            pipe(...$configurators)(new DOMDocument())
        );
    }

    /**
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

    public function locate(callable $locator)
    {
        return $locator($this->document);
    }

    public function manipulate(callable $manipulator)
    {
        $manipulator($this->document);

        return $this;
    }

    /**
     * @param list<callable(DOMDocument): list<DOMNode>|DOMNode> $builders
     *
     * @return list<DOMNode>
     */
    public function build(callable ... $builders): array
    {
        return reduce(
            $builders,
            function ($builds, callable $builder) {
                $result = $builder($this->document);
                $newBuilds = is_array($result) ? $result : [$result];

                return [...$builds, $newBuilds];
            },
            []
        );
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
        return Xpath::fromDocument($this, $configurators);
    }
}
