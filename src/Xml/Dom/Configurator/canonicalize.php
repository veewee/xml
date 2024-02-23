<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument as DOMDocument;
use VeeWee\Xml\Dom\Document;
use function Psl\Type\non_empty_string;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

/**
 * @return Closure(DOMDocument): DOMDocument
 */
function canonicalize(): Closure
{
    return static fn (DOMDocument $document): DOMDocument
        => Document::fromLoader(
            xml_string_loader(
                non_empty_string()->assert($document->C14N()),
                LIBXML_NSCLEAN + LIBXML_NOCDATA
            ),
            pretty_print(),
            normalize()
        )->toUnsafeDocument();
}
