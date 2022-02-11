<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use DOMDocument;

/**
 * @param non-empty-string $xml
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return callable(DOMDocument): void
 */
function xml_string_loader(string $xml, int $options = 0): callable
{
    return static function (DOMDocument $document) use ($xml, $options): void {
        load(static fn (): bool => (bool) $document->loadXML($xml, $options));
    };
}
