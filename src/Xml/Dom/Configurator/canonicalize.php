<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument as DOMDocument;
use VeeWee\Xml\Dom\Document;
use function Psl\Type\non_empty_string;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function canonicalize(): Closure
{
    return static fn (DOMDocument $document): DOMDocument
        => Document::fromXmlString(
            non_empty_string()->assert($document->C14N()), // TODO : LIBXML_NSCLEAN + LIBXML_NOCDATA
            // pretty_print() : TODO
            normalize()
        )->toUnsafeDocument();
}
