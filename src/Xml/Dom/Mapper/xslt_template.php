<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Mapper;

use Closure;
use VeeWee\Xml\Dom\Document;
use DOM\XMLDocument;
use VeeWee\Xml\Xslt\Processor;
use XSLTProcessor;

/**
 * @param list<callable(XSLTProcessor): XSLTProcessor> $configurators
 * @return Closure(XMLDocument): string
 */
function xslt_template(Document $template, callable ... $configurators): Closure
{
    return static fn (XMLDocument $document): string =>
        Processor::fromTemplateDocument($template, ...$configurators)->transformDocumentToString(
            Document::fromUnsafeDocument($document)
        );
}
