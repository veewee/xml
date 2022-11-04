<?php

declare(strict_types=1);

namespace VeeWee\Xml\Xslt;

use VeeWee\Xml\Dom\Document;
use XSLTProcessor;
use function VeeWee\Xml\Dom\Mapper\from_template_document;
use function VeeWee\Xml\Internal\configure;
use function VeeWee\Xml\Xslt\Configurator\loader;
use function VeeWee\Xml\Xslt\Transformer\document_to_string;

final class Processor
{
    private XSLTProcessor $processor;

    private function __construct(XSLTProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param list<callable(XSLTProcessor): XSLTProcessor> $configurators
     */
    public static function configure(callable ... $configurators): self
    {
        return new self(
            configure(...$configurators)(new XSLTProcessor())
        );
    }

    /**
     * @param list<callable(XSLTProcessor): XSLTProcessor> $configurators
     */
    public static function fromTemplateDocument(Document $template, callable ... $configurators): self
    {
        return self::configure(
            loader(from_template_document($template)),
            ...$configurators
        );
    }

    /**
     * @template T
     * @param callable(XSLTProcessor): T $transformer
     * @return T
     */
    public function transform(callable $transformer): mixed
    {
        return $transformer($this->processor);
    }

    public function transformDocumentToString(Document $document): string
    {
        return $this->transform(document_to_string($document));
    }
}
