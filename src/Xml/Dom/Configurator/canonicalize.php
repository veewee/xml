<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use Dom\XMLDocument;
use VeeWee\Xml\Dom\Document;
use function Psl\Type\non_empty_string;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

/**
 * @return Closure(XMLDocument): XMLDocument
 */
function canonicalize(): Closure
{
    return static function (XMLDocument $document): XMLDocument {
        if (!$document->documentElement) {
            return $document;
        }

        // Round-trip through saveXml first to normalize namespace declarations.
        // C14N on DOM-manipulated documents can produce duplicate xmlns attributes
        // on certain libxml versions (e.g. 2.9.14), causing createFromString to hang.
        // @see https://github.com/php/php-src/issues/XXXXX
        $normalized = XMLDocument::createFromString(
            non_empty_string()->assert($document->saveXml()),
        );

        return Document::fromLoader(
            xml_string_loader(
                non_empty_string()->assert($normalized->C14N()),
                LIBXML_NSCLEAN + LIBXML_NOCDATA
            ),
            pretty_print(),
            normalize(),
        )->toUnsafeDocument();
    };
}
