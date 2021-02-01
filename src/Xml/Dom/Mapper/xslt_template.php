<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use VeeWee\Xml\Xslt\Processor;
use XSLTProcessor;

/**
 * @param list<callable(XSLTProcessor): XSLTProcessor> $configurators
 * @return callable(DOMDocument): string
 */
function xslt_template(Document $template, callable ... $configurators): callable
{
    return static fn (DOMDocument $document): string
        => Processor::fromTemplateDocument($template, ...$configurators)->transformDocumentToString(
            Document::fromUnsafeDocument($document)
        );
}
