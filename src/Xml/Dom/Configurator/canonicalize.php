<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use DOMDocument;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;
use function VeeWee\Xml\ErrorHandling\disallow_libxml_false_returns;

/**
 * @return callable(DOMDocument): DOMDocument
 */
function canonicalize(): callable
{
    return static fn (DOMDocument $document): DOMDocument
        => Document::configure(
            pretty_print(),
            loader(
                xml_string_loader(
                    disallow_libxml_false_returns(
                        $document->C14N(),
                        'Could not canonicalize XML input.'
                    ),
                    LIBXML_NSCLEAN + LIBXML_NOCDATA
                )
            ),
            normalize()
        )->toUnsafeDocument();
}
