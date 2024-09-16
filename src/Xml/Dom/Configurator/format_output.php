<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Configurator;

use Closure;
use \Dom\XMLDocument;

/**
 * Consider using it together with LIBXML_NOEMPTYTAG in the XML loader.
 *
 * @return Closure(\Dom\XMLDocument): \Dom\XMLDocument
 */
function format_output(bool $formatOutput = true): Closure
{
    return static function (\Dom\XMLDocument $document) use ($formatOutput) : \Dom\XMLDocument {
        $document->formatOutput = $formatOutput;

        return $document;
    };
}
