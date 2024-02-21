<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Closure;
use DOMDocument as DOMDocument;
use VeeWee\XmlDOMDocument;
use VeeWee\Xml\Xslt\Processor;
use XSLTProcessor;

/**
 * @param list<callable(XSLTProcessor): XSLTProcessor> $configurators
 * @return Closure(DOMDocument): string
 */
function xslt_template(Document $template, callable ... $configurators): Closure
{
    return static fn (DOMDocument $document): string
        => Processor::fromTemplateDocument($template, ...$configurators)->transformDocumentToString(
            Document::fromUnsafeDocument($document)
        );
}
