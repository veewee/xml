<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \DOM\XMLDocument;
use VeeWee\Xml\Dom\Document;
use function VeeWee\Xml\Dom\Loader\xml_string_loader;

/**
 * @return Closure(\DOM\XMLDocument): \DOM\XMLDocument
 */
function trim_spaces(): Closure
{
    return static function (\DOM\XMLDocument $document): \DOM\XMLDocument {
        $trimmed = Document::fromLoader(
            xml_string_loader(
                Document::fromUnsafeDocument($document)->toXmlString(),
                LIBXML_NOBLANKS
            )
        )->toUnsafeDocument();

        $trimmed->formatOutput = false;

        return $trimmed;
    };
}
