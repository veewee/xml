<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use \DOM\XMLDocument;

/**
 * @param non-empty-string $xml
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return Closure(\DOM\XMLDocument): void
 */
function xml_string_loader(string $xml, int $options = 0): Closure
{
    return static function (\DOM\XMLDocument $document) use ($xml, $options): void {
        load(static fn (): bool => $document->loadXML($xml, $options));
    };
}
