<?php

declare(strict_types=1);

namespace VeeWee\Xml\Dom\Loader;

use Closure;
use \Dom\XMLDocument;
use function VeeWee\Xml\ErrorHandling\disallow_issues;

/**
 * @param non-empty-string $xml
 * @param int $options - bitmask of LIBXML_* constants https://www.php.net/manual/en/libxml.constants.php
 * @return Closure(): XMLDocument
 */
function xml_string_loader(string $xml, int $options = 0, ?string $override_encoding = null): Closure
{
    return static fn () => disallow_issues(static function () use ($xml, $options, $override_encoding): XMLDocument {
        return XMLDocument::createFromString($xml, $options, $override_encoding);
    });
}
